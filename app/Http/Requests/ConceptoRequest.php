<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConceptoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'codigo'        => 'required|string|max:50',
            'descripcion'   => 'required|string|max:255',
            'tipo'          => 'required|in:HABER,DESCUENTO',
            'monto_default' => 'nullable|numeric',

            'modo_calculo'  => 'required|in:FIJO,PORCENTAJE,MEJOR_SUELDO,FORMULA',
            'valor_calculo' => 'nullable|numeric'
        ];
    }
}
