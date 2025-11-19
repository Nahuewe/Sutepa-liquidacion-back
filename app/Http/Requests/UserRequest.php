<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user');

        return [
            'nombre'       => 'sometimes|nullable|string|max:255',
            'apellido'     => 'sometimes|nullable|string|max:255',
            'dni'          => 'sometimes|nullable|string|max:255',
            'legajo'       => ['sometimes','nullable','string','max:255', Rule::unique('users')->ignore($id)],
            'roles_id'     => 'sometimes|nullable|exists:roles,id',
            'seccional_id' => 'sometimes|nullable|exists:seccional,id'
        ];
    }

    public function messages(): array
    {
        return [
            'legajo.unique' => 'El legajo ya está registrado.',
        ];
    }
}
