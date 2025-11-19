<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeccionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo'          => 'required|string|max:255',
            'identificador' => 'required|string|max:255',
            'contenido'     => 'required|string',
        ];
    }
}
