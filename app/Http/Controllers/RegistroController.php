<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomizeException;
use App\Exports\EgresosExport;
use App\Exports\IngresosExport;
use App\Http\Resources\RegistroResource;
use App\Models\Auditoria;
use App\Models\Egreso;
use App\Models\Ingreso;
use App\Services\RegistroService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class RegistroController extends Controller
{
    protected $RegistroService;

    public function __construct(RegistroService $RegistroService)
    {
        $this->RegistroService = $RegistroService;
    }

    public function registrarIngreso(Request $request)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string',
            'apellido'     => 'required|string',
            'dni'          => 'required|string',
            'legajo'       => 'required|string',
            'seccional'    => 'nullable|string',
            'seccional_id' => 'nullable|numeric',
        ]);

        if (isset($validated['seccional_id'])) {
            $validated['seccional_id'] = (int) $validated['seccional_id'];
        }

        $ingreso = $this->RegistroService->registrarIngreso($validated);

        Auditoria::create([
            'user_id'    => auth()->id(),
            'accion'     => 'Ingreso',
            'modelo'     => 'Ingreso',
            'modelo_id'  => $ingreso->id ?? null,
            'datos'      => json_encode($validated),
        ]);

        return response()->json(['message' => 'Ingreso registrado correctamente']);
    }

    public function registrarEgreso(Request $request)
    {
        $validated = $request->validate([
            'legajo' => 'required|string',
        ]);

        try {
            $egreso = $this->RegistroService->registrarEgreso($validated);

            Auditoria::create([
                'user_id'    => auth()->id(),
                'accion'     => 'Egreso',
                'modelo'     => 'Egreso',
                'modelo_id'  => $egreso->id ?? null,
                'datos'      => json_encode($validated),
            ]);

            return response()->json(['message' => 'Egreso registrado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function getIngresos(Request $request)
    {
        $page     = $request->query('page', 1);
        $ingresos = Ingreso::with('asistente')
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'data' => $ingresos->items(),
            'meta' => [
                'total'        => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'last_page'    => $ingresos->lastPage(),
                'per_page'     => $ingresos->perPage(),
                'from'         => $ingresos->firstItem(),
                'to'           => $ingresos->lastItem(),
            ]
        ]);
    }

    public function getEgresos(Request $request)
    {
        $page    = $request->query('page', 1);
        $egresos = Egreso::with('asistente')
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'data' => $egresos->items(),
            'meta' => [
                'total'        => $egresos->total(),
                'current_page' => $egresos->currentPage(),
                'last_page'    => $egresos->lastPage(),
                'per_page'     => $egresos->perPage(),
                'from'         => $egresos->firstItem(),
                'to'           => $egresos->lastItem(),
            ]
        ]);
    }

    public function buscarRegistro(Request $request)
    {
        try {
            $query    = $request->input('query');
            $page     = $request->query('page', 1);
            $ingresos = $this->RegistroService->buscarRegistro($query, $page);

            return response()->json([
                'data' => RegistroResource::collection($ingresos->items()),
                'meta' => [
                    'total'        => $ingresos->total(),
                    'current_page' => $ingresos->currentPage(),
                    'last_page'    => $ingresos->lastPage(),
                    'per_page'     => $ingresos->perPage(),
                    'from'         => $ingresos->firstItem(),
                    'to'           => $ingresos->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            throw new CustomizeException('Error al buscar registros: ' . $e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function exportarIngresos()
    {
        return Excel::download(new IngresosExport(), 'ingresos.xlsx');
    }

    public function exportarEgresos()
    {
        return Excel::download(new EgresosExport(), 'egresos.xlsx');
    }
}
