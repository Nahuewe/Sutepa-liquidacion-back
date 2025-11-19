<?php

namespace App\Http\Controllers;

use App\Exports\VotacionesExport;
use App\Http\Resources\VotacionResource;
use App\Models\Auditoria;
use App\Models\User;
use App\Models\Votacion;
use App\Services\VotacionService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class VotacionController extends Controller
{
    protected VotacionService $votacionService;

    public function index()
    {
        $Votacion = $this->votacionService->VotacionLista();

        return VotacionResource::collection($Votacion);
    }

    public function show($id)
    {
        $votacion = $this->votacionService->verVotacion($id);

        if (!$votacion) {
            return response()->json(['message' => 'Votaci車n no encontrada'], 404);
        }

        return new VotacionResource($votacion);
    }

    public function __construct(VotacionService $votacionService)
    {
        $this->votacionService = $votacionService;
    }

    public function store(Request $request)
    {
        $votacion = $this->votacionService->crearVotacion($request->all());

        Auditoria::create([
            'user_id'    => auth()->id(),
            'accion'     => 'Creación',
            'modelo'     => 'Votacion',
            'modelo_id'  => $votacion->id,
            'datos'      => json_encode($votacion),
        ]);

        return response()->json($votacion);
    }

    public function usuariosNoVotaron(Votacion $votacion)
    {
        $usuarios = User::whereNotIn('id', function ($query) use ($votacion) {
            $query->select('asistente_id')
                ->from('votos')
                ->where('votacion_id', $votacion->id);
        })
            ->select('id as asistente_id', 'nombre', 'apellido')
            ->get();

        return response()->json($usuarios);
    }

    protected function utf8ize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        }

        return $data;
    }

    public function detener($id)
    {
        $votacion = Votacion::find($id);

        if (!$votacion) {
            return response()
                ->json(
                    ['message' => 'Votación no encontrada'],
                    404,
                    [],
                    JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE
                );
        }

        $votacion->activa_hasta = now();
        $votacion->save();

        Auditoria::create([
            'user_id'    => auth()->id(),
            'accion'     => 'Detener',
            'modelo'     => 'Votacion',
            'modelo_id'  => $id,
            'datos'      => json_encode($votacion),
        ]);

        $response = [
            'message'  => 'Votación detenida correctamente',
            'votacion' => $votacion,
        ];

        $clean = $this->utf8ize($response);

        return response()
            ->json(
                $clean,
                200,
                [],
                JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE
            );
    }

    public function exportarVotaciones()
    {
        return Excel::download(new VotacionesExport(), 'votaciones.xlsx');
    }
}
