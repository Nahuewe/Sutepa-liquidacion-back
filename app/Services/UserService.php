<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function UserLista()
    {
        $User = User::orderBy('apellido', 'asc')->paginate(10);

        return $User;
    }

    public function verUser($id)
    {
        $User = User::where('id', $id)->first();

        return $User;
    }

    public function buscarUser($query)
    {
        $usuarios = User::where('legajo', 'LIKE', "%$query%")
            ->orWhere('nombre', 'LIKE', "%$query%")
            ->orWhere('apellido', 'LIKE', "%$query%")
            ->get();

        return $usuarios;
    }

    public function UserActualizar($id, $data)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->nombre       = $data['nombre']       ?? $user->nombre;
        $user->apellido     = $data['apellido']     ?? $user->apellido;
        $user->dni          = $data['dni']          ?? $user->dni;
        $user->legajo       = $data['legajo']       ?? $user->legajo;
        $user->roles_id     = $data['roles_id']     ?? $user->roles_id;
        $user->seccional_id = $data['seccional_id'] ?? $user->seccional_id;
        $user->save();

        return $user;
    }

    public function eliminarUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return null;
        }

        $user->delete();

        return $user;
    }
}
