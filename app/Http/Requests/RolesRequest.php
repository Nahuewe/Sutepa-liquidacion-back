<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El campo nombre del rol es obligatorio.',
            'nombre.string'   => 'El campo nombre del rol debe ser una cadena de texto.',
            'nombre.max'      => 'El campo nombre del rol no puede tener más de 255 caracteres.',
        ];
    }
}
