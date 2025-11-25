<?php

namespace App\Http\Controllers;

use App\Http\Requests\LiquidacionRequest;
use App\Models\Auditoria;
use App\Models\Liquidacion;
use App\Services\LiquidacionService;
use Illuminate\Http\Request;
use App\Exports\LiquidacionesExport;
use Maatwebsite\Excel\Facades\Excel;

class LiquidacionController extends Controller
{
    protected $service;

    public function __construct(LiquidacionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $req)
    {
        $perPage = $req->get('per_page', 15);
        $search = $req->get('search');

        $q = Liquidacion::with('empleado')->orderBy('created_at', 'desc');

        if ($req->has('periodo')) {
            $q->where('periodo', $req->get('periodo'));
        }

        if (!empty($search)) {
            $q->where(function ($query) use ($search) {
                $query
                    ->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('periodo', 'LIKE', "%{$search}%")
                    ->orWhere('estado', 'LIKE', "%{$search}%")
                    ->orWhereHas('empleado', function ($emp) use ($search) {
                        $emp->where('nombre', 'LIKE', "%{$search}%")
                            ->orWhere('apellido', 'LIKE', "%{$search}%")
                            ->orWhere('cuil', 'LIKE', "%{$search}%");
                    });
            });
        }

        $paginator = $q->paginate($perPage);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'last_page'    => $paginator->lastPage(),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
        ]);
    }

    public function show($id)
    {
        return Liquidacion::with(['empleado', 'items.concepto'])->findOrFail($id);
    }

    public function store(LiquidacionRequest $request)
    {
        $validated = $request->validated();
        $liquidacion = $this->service->create($validated);

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Crear',
            'modelo'    => 'Liquidacion',
            'modelo_id' => $liquidacion->id,
            'datos'     => json_encode($validated),
        ]);

        return response()->json($liquidacion, 201);
    }

    public function update(LiquidacionRequest $request, $id)
    {
        $liquidacion = Liquidacion::findOrFail($id);

        $validated = $request->validated();

        $liquidacion->update([
            'empleado_id' => $validated['empleado_id'],
            'periodo'     => $validated['periodo']
        ]);

        $liquidacion->items()->delete();

        foreach ($validated['items'] as $item) {
            $liquidacion->items()->create($item);
        }

        $totalHaberes = $liquidacion->items()->where('tipo', 'HABER')->sum('monto');
        $totalDescuentos = $liquidacion->items()->where('tipo', 'DESCUENTO')->sum('monto');
        $neto = $totalHaberes - $totalDescuentos;

        $liquidacion->update([
            'total_haberes'     => $totalHaberes,
            'total_descuentos'  => $totalDescuentos,
            'neto'              => $neto,
        ]);

        // Si estaba pagada y la editan, vuelve a PENDIENTE
        if ($liquidacion->estado === 'PAGADA') {
            $liquidacion->update([
                'estado' => 'PENDIENTE',
                'pagada_at' => null
            ]);
        }

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Actualizar',
            'modelo'    => 'Liquidacion',
            'modelo_id' => $id,
            'datos'     => json_encode($validated),
        ]);

        return response()->json(['message' => 'Actualizado']);
    }

    public function markAsPaid($id)
    {
        $liq = Liquidacion::findOrFail($id);
        $this->service->markAsPaid($liq);

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Marcar Pagada',
            'modelo'    => 'Liquidacion',
            'modelo_id' => $id,
            'datos'     => json_encode($liq),
        ]);

        return response()->json(['message' => 'Marcada como pagada']);
    }

    public function destroy($id)
    {
        $liq = Liquidacion::findOrFail($id);
        $liq->items()->delete();
        $liq->delete();

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Eliminar',
            'modelo'    => 'Liquidacion',
            'modelo_id' => $id,
            'datos'     => json_encode($liq),
        ]);

        return response()->json(['message' => 'Eliminada correctamente']);
    }

    public function export(Request $req)
    {
        $periodo = $req->get('periodo');

        return Excel::download(
            new LiquidacionesExport($periodo),
            'liquidaciones.xlsx'
        );
    }
}
