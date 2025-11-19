<?php

namespace App\Http\Controllers;

use App\Exports\VotosExport;
use App\Http\Resources\VotoResource;
use App\Models\Voto;
use App\Services\VotoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VotoController extends Controller
{
    protected VotoService $VotoService;

    public function index()
    {
        $Voto = $this->VotoService->VotoLista();

        return VotoResource::collection($Voto);
    }

    public function __construct(VotoService $VotoService)
    {
        $this->VotoService = $VotoService;
    }

        public function store(Request $request)
    {
        $Voto = $this->VotoService->registrarVoto($request->all());

        return response()->json($Voto);
    }

    public function show($id)
    {
        $Voto = $this->VotoService->verVoto($id);

        if (!$Voto) {
            return response()->json(['message' => 'Votación no encontrada'], 404);
        }

        return new VotoResource($Voto);
    }

    public function verificarVoto(Request $request)
    {
        $request->validate([
            'votacion_id'  => 'required|exists:votacions,id',
            'asistente_id' => 'required|exists:users,id',
        ]);

        $yaVoto = Voto::where('votacion_id', $request->votacion_id)
                    ->where('asistente_id', $request->asistente_id)
                    ->exists();

        return response()->json(['ya_voto' => $yaVoto]);
    }

    public function votosPorVotacion($votacion_id)
    {
        $votos = Voto::with(['asistente' => function ($query) {
            $query->select('id', 'nombre', 'apellido');
        }])
                    ->where('votacion_id', $votacion_id)
                    ->select('id', 'votacion_id', 'asistente_id', 'respuesta', 'created_at')
                    ->get()
                    ->map(function ($voto) {
                        return [
                            'asistente_id' => $voto->asistente_id,
                            'nombre'       => $voto->asistente->nombre   ?? 'Desconocido',
                            'apellido'     => $voto->asistente->apellido ?? 'Desconocido',
                            'respuesta'    => $voto->respuesta,
                            'ya_voto'      => true,
                            'created_at'   => $voto->created_at,
                        ];
                    });

        return response()->json($votos);
    }

    public function respuestasPorVotaciones(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:votacions,id',
        ]);

        $respuestas = Voto::whereIn('votacion_id', $request->ids)
            ->select('votacion_id', 'respuesta', DB::raw('count(*) as total'))
            ->groupBy('votacion_id', 'respuesta')
            ->get();

        $resultadosAgrupados = [];
        foreach ($request->ids as $votacionId) {
            $resultadosAgrupados[$votacionId] = [
                'afirmativo' => 0,
                'negativo'   => 0,
                'abstencion' => 0,
            ];
        }

        foreach ($respuestas as $respuesta) {
            if (isset($resultadosAgrupados[$respuesta->votacion_id])) {
                $tipoRespuesta = strtolower($respuesta->respuesta);
                if (isset($resultadosAgrupados[$respuesta->votacion_id][$tipoRespuesta])) {
                    $resultadosAgrupados[$respuesta->votacion_id][$tipoRespuesta] = $respuesta->total;
                }
            }
        }

        return response()->json($resultadosAgrupados);
    }

    public function exportarVotos()
    {
        return Excel::download(new VotosExport(), 'votos.xlsx');
    }
}
