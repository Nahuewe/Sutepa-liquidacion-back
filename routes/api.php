<?php

use App\Http\Controllers\{
    AuditoriaController,
    AuthController,
    ConceptoController,
    EmpleadoController,
    LiquidacionController,
    RolesController,
    SeccionalController,
    UserController
};
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/registrar', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->middleware('auth:sanctum');

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {

    // Liquidacion
    Route::get('/liquidaciones/export', [LiquidacionController::class, 'export']);
    Route::get('/liquidaciones', [LiquidacionController::class, 'index']);
    Route::post('/liquidaciones', [LiquidacionController::class, 'store']);
    Route::get('/liquidaciones/{id}', [LiquidacionController::class, 'show']);
    Route::put('/liquidaciones/{id}', [LiquidacionController::class, 'update']);
    Route::post('/liquidaciones/{id}/pagar', [LiquidacionController::class, 'markAsPaid']);
    Route::delete('/liquidaciones/{id}', [LiquidacionController::class, 'destroy']);

    // Conceptos
    Route::apiResource('/conceptos', ConceptoController::class);
    Route::post('/conceptos/{id}/calcular', [ConceptoController::class, 'calcular']);

    // Empleados
    Route::apiResource('/empleados', EmpleadoController::class);

    // Auditoria
    Route::get('/auditoria/exportar', [AuditoriaController::class, 'exportarAuditoria']);
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->middleware('auth');

    // Usuarios
    Route::get('buscar-user', [UserController::class, 'buscarUser']);
    Route::apiResource('/user', UserController::class);
    Route::apiResource('/roles', RolesController::class);
    Route::apiResource('/seccionales', SeccionalController::class);
});
