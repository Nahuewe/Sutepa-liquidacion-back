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
            'codigo'        => 'required|string|unique:conceptos,codigo',
            'descripcion'   => 'required|string',
            'tipo'          => 'required|in:HABER,DESCUENTO',
            'monto'         => 'nullable|numeric',
        ]);

        // Adaptar a la columna real
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
            'codigo'        => "required|string|unique:conceptos,codigo,$id",
            'descripcion'   => 'required|string',
            'tipo'          => 'required|in:HABER,DESCUENTO',
            'monto'         => 'nullable|numeric',
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
}
