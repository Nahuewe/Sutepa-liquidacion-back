<?php

namespace App\Http\Controllers;

use App\Http\Requests\LiquidacionRequest;
use App\Models\Auditoria;
use App\Models\Liquidacion;
use App\Services\LiquidacionService;
use Illuminate\Http\Request;

class LiquidacionController extends Controller
{
    protected $service;

    public function __construct(LiquidacionService $service) {
        $this->service = $service;
    }

    public function index(Request $req) {
        $perPage = $req->get('per_page', 15);
        $q = Liquidacion::with('empleado')->orderBy('created_at','desc');
        if ($req->has('periodo')) $q->where('periodo', $req->get('periodo'));
        return $q->paginate($perPage);
    }

    public function show($id) {
        return Liquidacion::with(['empleado','items.concepto'])->findOrFail($id);
    }

    public function store(LiquidacionRequest $request) {
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

    public function update(LiquidacionRequest $request, $id) {
        $liquidacion = Liquidacion::findOrFail($id);

        $validated = $request->validated();

        $liquidacion->items()->delete();
        $this->service->create(array_merge($validated, [
            'empleado_id' => $liquidacion->empleado_id,
            'periodo'     => $liquidacion->periodo
        ]));

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Actualizar',
            'modelo'    => 'Liquidacion',
            'modelo_id' => $id,
            'datos'     => json_encode($validated),
        ]);

        return response()->json(['message'=>'Actualizado']);
    }

    public function markAsPaid($id) {
        $liq = Liquidacion::findOrFail($id);
        $this->service->markAsPaid($liq);

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Marcar Pagada',
            'modelo'    => 'Liquidacion',
            'modelo_id' => $id,
            'datos'     => json_encode($liq),
        ]);

        return response()->json(['message'=>'Marcada como pagada']);
    }
}
