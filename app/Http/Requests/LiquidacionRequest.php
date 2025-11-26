<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class LiquidacionRequest extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'empleado_id' => 'required|exists:empleados,id',
            'periodo' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.concepto_id' => 'nullable|exists:conceptos,id',
            'items.*.tipo' => 'required|in:HABER,DESCUENTO',
            'items.*.codigo' => 'nullable|string|max:255',
            'items.*.descripcion' => 'nullable|string|max:500',
            'items.*.monto' => 'required|numeric',
        ];
    }
}
