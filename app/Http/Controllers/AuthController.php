<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'legajo'   => 'required|string|max:255|unique:users'
        ], [
            'legajo.unique' => 'El legajo ya está registrado, por favor, escanea la credencial del afiliado para reingresarlo.',
        ]);

        $user = User::create([
            'nombre'       => $request->nombre,
            'apellido'     => $request->apellido,
            'legajo'       => $request->legajo,
            'dni'          => $request->dni,
            'roles_id'     => $request->roles_id ?? 5,
            'seccional_id' => $request->seccional_id,
        ]);

        Auditoria::create([
            'user_id'    => auth()->id(),
            'accion'     => 'Creación',
            'modelo'     => 'Usuario',
            'modelo_id'  => $user->id,
            'datos'      => json_encode($user),
        ]);

        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'legajo' => 'required|string',
        ]);

        $user = User::where('legajo', $request->legajo)->first();

        if (! $user) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $token = $user->createToken($request->legajo)->plainTextToken;

        Auditoria::create([
            'user_id'    => $user->id,
            'accion'     => 'login',
            'modelo'     => 'Usuario',
            'modelo_id'  => $user->id,
            'datos'      => json_encode(['legajo' => $request->legajo]),
        ]);

        return response()->json([
            'token' => $token,
            'user'  => [
                "id"           => $user->id,
                "nombre"       => $user->nombre,
                "apellido"     => $user->apellido,
                "dni"          => $user->dni,
                "rol"          => $user->rol->nombre,
                "roles_id"     => (int) $user->roles_id,
                "seccional"    => $user->seccional->nombre,
                "seccional_id" => (int) $user->seccional_id,
                "legajo"       => $user->legajo
            ]
        ]);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'error' => 'Usuario no autenticado.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $currentToken = $user->currentAccessToken();

        if (! $currentToken) {
            return response()->json([
                'error' => 'Token no válido.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $newToken = $user->createToken('NewToken')->plainTextToken;

        return response()->json([
            'token' => $newToken,
            'user'  => [
                "id"           => $user->id,
                "nombre"       => $user->nombre,
                "apellido"     => $user->apellido,
                "dni"          => $user->dni,
                "rol"          => $user->rol->nombre,
                "roles_id"     => (int) $user->roles_id,
                "seccional"    => $user->seccional->nombre,
                "seccional_id" => (int) $user->seccional_id,
                "legajo"       => $user->legajo
            ]
        ], Response::HTTP_OK);
    }
}
