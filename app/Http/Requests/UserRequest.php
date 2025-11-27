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
            'nombre'       => 'sometimes|string|max:255',
            'apellido'     => 'sometimes|string|max:255',
            'dni'          => 'sometimes|string|max:255',
            'legajo'       => 'sometimes|string|max:255',
            'username'     => ['sometimes', 'string', 'max:255', Rule::unique('users')->ignore($id)],
            'password'     => 'sometimes|string|min:6',
            'telefono'     => 'sometimes|nullable|string|max:255',
            'correo'       => 'sometimes|nullable|email|max:255',
            'roles_id'     => 'sometimes|exists:roles,id',
            'estados_id'   => 'sometimes|exists:estados,id',
            'seccional_id' => 'sometimes|exists:seccional,id',
        ];
    }

    public function messages(): array
    {
        return [
            'legajo.unique' => 'El legajo ya está registrado.',
        ];
    }
}
