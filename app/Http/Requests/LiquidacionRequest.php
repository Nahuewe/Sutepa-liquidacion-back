<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreLiquidacionRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'empleado_id' => 'required|exists:empleados,id',
            'periodo' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.tipo' => 'required|in:HABER,DESCUENTO',
            'items.*.monto' => 'required|numeric',
            // optional: concepto_id exists
            'items.*.concepto_id' => 'nullable|exists:conceptos,id',
        ];
    }
}

