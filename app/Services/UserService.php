<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function UserLista()
    {
        return User::orderBy('apellido', 'asc')->paginate(10);
    }

    public function verUser($id)
    {
        return User::find($id);
    }

    public function buscarUser($query)
    {
        return User::where('username', 'LIKE', "%$query%")
            ->orWhere('nombre', 'LIKE', "%$query%")
            ->orWhere('apellido', 'LIKE', "%$query%")
            ->get();
    }

    public function UserActualizar($id, $data)
    {
        $user = User::find($id);

        if (!$user) {
            return null;
        }

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return $user;
    }

    public function eliminarUser($id)
    {
        $user = User::find($id);

        if (!$user) return null;

        $user->delete();

        return $user;
    }
}
