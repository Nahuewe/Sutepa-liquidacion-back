<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Concepto;
use Illuminate\Http\Request;

class ConceptoController extends Controller
{
    public function index(Request $req)
    {
        $perPage = $req->get('per_page', 15);
        $search = $req->get('search');

        $query = Concepto::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('codigo', 'LIKE', "%{$search}%");
            });
        }

        $paginator = $query->orderBy('codigo')->paginate($perPage);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'last_page'    => $paginator->lastPage(),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo'          => 'required|string|unique:conceptos,codigo',
            'descripcion'     => 'required|string',
            'tipo'            => 'required|in:HABER,DESCUENTO',
            'monto'           => 'nullable|numeric',
            'modo_calculo'    => 'required|in:FIJO,PORCENTAJE,MEJOR_SUELDO',
            'valor_calculo'   => 'nullable|numeric',
        ]);

        $validated['monto_default'] = $validated['monto'];
        unset($validated['monto']);

        $concepto = Concepto::create($validated);

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Crear',
            'modelo'    => 'Concepto',
            'modelo_id' => $concepto->id,
            'datos'     => json_encode($validated),
        ]);

        return response()->json($concepto, 201);
    }

    public function show($id)
    {
        return response()->json(Concepto::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $concepto = Concepto::findOrFail($id);

        $validated = $request->validate([
            'codigo'          => "required|string|unique:conceptos,codigo,$id",
            'descripcion'     => 'required|string',
            'tipo'            => 'required|in:HABER,DESCUENTO',
            'monto'           => 'nullable|numeric',
            'modo_calculo'    => 'required|in:FIJO,PORCENTAJE,MEJOR_SUELDO',
            'valor_calculo'   => 'nullable|numeric',
        ]);

        $validated['monto_default'] = $validated['monto'];
        unset($validated['monto']);

        $concepto->update($validated);

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Actualizar',
            'modelo'    => 'Concepto',
            'modelo_id' => $id,
            'datos'     => json_encode($validated),
        ]);

        return response()->json($concepto);
    }

    public function destroy($id)
    {
        $concepto = Concepto::findOrFail($id);

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Eliminar',
            'modelo'    => 'Concepto',
            'modelo_id' => $id,
            'datos'     => json_encode($concepto),
        ]);

        $concepto->delete();

        return response()->json(['message' => 'Concepto eliminado']);
    }

    public function calcular(Request $request)
    {
        $request->validate([
            'concepto_id' => 'required|exists:conceptos,id',
            'items' => 'required|array',
            'items.*.codigo' => 'required|string',
            'items.*.monto' => 'nullable|numeric'
        ]);

        $concepto = Concepto::find($request->concepto_id);

        if ($concepto->modo_calculo === 'FIJO') {
            return response()->json([
                'monto' => $concepto->monto_default ?? 0
            ]);
        }

        if ($concepto->modo_calculo === 'PORCENTAJE') {

            $base = collect($request->items)
                ->firstWhere('codigo', 'BAS');

            if (!$base) {
                return response()->json(['monto' => 0]);
            }

            $monto = ($base['monto'] ?? 0) * ($concepto->valor_calculo / 100);

            return response()->json([
                'monto' => round($monto, 2)
            ]);
        }

        return response()->json(['monto' => 0]);
    }

    public function calcularConcepto(Concepto $concepto, $empleado)
    {
        switch ($concepto->modo_calculo) {

            case 'FIJO':
                return $concepto->monto_default;

            case 'PORCENTAJE_MEJOR_SUELDO':
                $mejor = $empleado->sueldos()->max('monto');
                return $mejor * ($concepto->valor_calculo / 100);

            default:
                return 0;
        }
    }
}
