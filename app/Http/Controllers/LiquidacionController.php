<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLiquidacionRequest;
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

    public function store(StoreLiquidacionRequest $request) {
        $liquidacion = $this->service->create($request->validated());
        return response()->json($liquidacion, 201);
    }

    public function update(StoreLiquidacionRequest $request, $id) {
        $liquidacion = Liquidacion::findOrFail($id);
        // simplificamos: borro items y vuelvo a crear utilizando service
        $liquidacion->items()->delete();
        $this->service->create(array_merge($request->validated(), ['empleado_id' => $liquidacion->empleado_id, 'periodo' => $liquidacion->periodo]));
        return response()->json(['message'=>'Actualizado']);
    }

    public function markAsPaid($id) {
        $liq = Liquidacion::findOrFail($id);
        $this->service->markAsPaid($liq);
        return response()->json(['message'=>'Marcada como pagada']);
    }
}
