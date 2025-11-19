<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrdenDiariaResource;
use App\Models\Auditoria;
use App\Services\OrdenDiariaService;
use Illuminate\Http\Request;

class OrdenDiariaController extends Controller
{
    protected $ordenDiarioService;

    public function __construct(OrdenDiariaService $ordenDiarioService)
    {
        $this->ordenDiarioService = $ordenDiarioService;
    }

    public function index()
    {
        $ordenes = $this->ordenDiarioService->ordenDiariaLista();

        return OrdenDiariaResource::collection($ordenes);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo'          => 'required|string|max:255',
            'identificador' => 'required|string',
            'contenido'     => 'required|string',
        ]);

        $orden = $this->ordenDiarioService->crearOrdenDiaria($data);

        Auditoria::create([
            'user_id'    => auth()->id(),
            'accion'     => 'Creación',
            'modelo'     => 'Orden Diaria',
            'modelo_id'  => $orden->id,
            'datos'      => json_encode($orden),
        ]);

        return new OrdenDiariaResource($orden);
    }

    public function show($id)
    {
        $orden = $this->ordenDiarioService->obtenerPorId($id);

        return new OrdenDiariaResource($orden);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'tipo'          => 'required|string|max:255',
            'identificador' => 'required|string',
            'contenido'     => 'required|string',
        ]);

        $orden = $this->ordenDiarioService->editarOrdenDiaria($id, $data);

        return new OrdenDiariaResource($orden);
    }

    public function destroy($id)
    {
        $orden = $this->ordenDiarioService->obtenerPorId($id);

        if (!$orden) {
            return response()->json(['message' => 'Orden Diaria no encontrada'], 404);
        }

        $this->ordenDiarioService->eliminarOrdenDiaria($id);

        Auditoria::create([
            'user_id'    => auth()->id(),
            'accion'     => 'Eliminar',
            'modelo'     => 'Orden Diaria',
            'modelo_id'  => $id,
            'datos'      => json_encode($orden),
        ]);

        return response()->json(['message' => 'Orden eliminada correctamente'], 200);
    }
}
