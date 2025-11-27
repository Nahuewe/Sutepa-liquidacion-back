<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class AuthController extends Controller
{
    private const MAX_ATTEMPTS = 5;      // Intentos antes de bloquear
    private const BLOCK_MINUTES = 10;    // Duración del bloqueo

    public function register(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'nombre'       => strtoupper($request->nombre),
            'apellido'     => strtoupper($request->apellido),
            'dni'          => $request->dni,
            'roles_id'     => $request->roles_id ?? 5,
            'seccional_id' => $request->seccional_id,
            'password'     => Hash::make($request->password),
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
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        // -------------------------------------------------------
        // 🔒 1. CONTROLAR BLOQUEO ANTES DE VERIFICAR CONTRASEÑA
        // -------------------------------------------------------
        if ($user->is_blocked) {

            // Si por alguna razón last_login_attempt es null, lo bloqueo igual
            if (!$user->last_login_attempt) {
                return response()->json([
                    'error' => "Cuenta bloqueada temporalmente.",
                    'remaining_minutes' => self::BLOCK_MINUTES
                ], Response::HTTP_UNAUTHORIZED);
            }

            $diffSeconds = Carbon::parse($user->last_login_attempt)->diffInSeconds(now());
            $blockSeconds = self::BLOCK_MINUTES * 60;

            if ($diffSeconds < $blockSeconds) {
                $remaining = ceil(($blockSeconds - $diffSeconds) / 60);

                return response()->json([
                    'error' => "Cuenta bloqueada. Intenta nuevamente en $remaining minutos.",
                    'remaining_minutes' => $remaining,
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Si ya pasó el tiempo, desbloquear
            $user->update([
                'is_blocked' => false,
                'login_attempts' => 0,
                'last_login_attempt' => null,
            ]);
        }

        // -------------------------------------------------------
        // 🔐 2. VALIDAR CONTRASEÑA
        // -------------------------------------------------------
        if (! Hash::check($request->password, $user->password)) {

            $user->increment('login_attempts');
            $user->update(['last_login_attempt' => now()]);

            $remaining = self::MAX_ATTEMPTS - $user->login_attempts;

            // Si llegó al máximo → bloquear
            if ($user->login_attempts >= self::MAX_ATTEMPTS) {
                $user->update([
                    'is_blocked' => true,
                    'last_login_attempt' => now(),
                ]);

                return response()->json([
                    'error' => 'Tu cuenta fue bloqueada por demasiados intentos fallidos.',
                    'remaining' => 0,
                    'remaining_minutes' => self::BLOCK_MINUTES,
                ], Response::HTTP_UNAUTHORIZED);
            }

            return response()->json([
                'error' => 'Contraseña incorrecta.',
                'remaining' => $remaining,
            ], Response::HTTP_UNAUTHORIZED);
        }

        // -------------------------------------------------------
        // 🔓 3. SI LA CONTRASEÑA ES CORRECTA Y NO ESTÁ BLOQUEADO
        // -------------------------------------------------------
        $token = $user->createToken('access_token')->plainTextToken;

        Auditoria::create([
            'user_id'    => $user->id,
            'accion'     => 'Login',
            'modelo'     => 'Usuario',
            'modelo_id'  => $user->id,
            'datos'      => json_encode(['username' => $request->username]),
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
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        }

        $newToken = $user->createToken('refresh_token')->plainTextToken;

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
        ]);
    }
}
