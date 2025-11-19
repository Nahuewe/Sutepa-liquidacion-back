<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomizeException;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Auditoria;
use App\Services\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected $UserService;

    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }

    public function index()
    {
        $User = $this->UserService->UserLista();

        return UserResource::collection($User);
    }

    public function show($id)
    {
        $User = $this->UserService->verUser($id);

        return new UserResource($User);
    }

    public function update(UserRequest $request, $UserId)
    {
        try {
            $validated = $request->validated();

            $User = $this->UserService->UserActualizar($UserId, $validated);

            if (!$User) {
                return response()->json([
                    "message" => "User no encontrado",
                ], 404);
            }

            return response()->json([
                "message" => "User actualizado exitosamente",
            ], 200);
        } catch (\Exception $e) {
            throw new CustomizeException($e->getMessage(), \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    public function destroy($id)
    {
        try {
            $user = $this->UserService->verUser($id);
            $resultado = $this->UserService->eliminarUser($id);

            if (!$resultado) {
                return response()->json([
                    'message' => 'Usuario no encontrado',
                ], 404);
            }

            Auditoria::create([
                'user_id'    => auth()->id(),
                'accion'     => 'Eliminar',
                'modelo'     => 'Usuario',
                'modelo_id'  => $id,
                'datos'      => json_encode($user),
            ]);

            return response()->json([
                'message' => 'Usuario eliminado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            throw new CustomizeException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    public function buscarUser(UserRequest $request)
    {
        try {
            $query    = $request->input('query');
            $usuarios = $this->UserService->buscarUser($query);

            return UserResource::collection($usuarios);
        } catch (\Exception $e) {
            throw new CustomizeException('Seccional no encontrada', Response::HTTP_NOT_FOUND);
        }
    }
}
