<?php

use App\Http\Controllers\{
    AuditoriaController,
    AuthController,
    ConceptoController,
    EmpleadoController,
    LiquidacionController,
    OrdenDiariaController,
    RegistroController,
    RolesController,
    SeccionalController,
    UserController,
    VotacionController,
    VotoController
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

    // Empleados
    Route::apiResource('/empleados', EmpleadoController::class);

    // Votaciones
    Route::get('/votaciones/exportar', [VotacionController::class, 'exportarVotaciones']);
    Route::apiResource('/votaciones', VotacionController::class);
    Route::get('/votaciones/{id}/respuestas', [VotoController::class, 'votosPorVotacion']);
    Route::get('/votaciones/{votacion}/no-votaron', [VotacionController::class, 'usuariosNoVotaron']);
    Route::post('/votaciones/{id}/detener', [VotacionController::class, 'detener']);

    // Votos
    Route::get('/votos/exportar', [VotoController::class, 'exportarVotos']);
    Route::apiResource('/votos', VotoController::class);
    Route::post('/votos/respuestas-multiples', [VotoController::class, 'respuestasPorVotaciones']);
    Route::post('/votos/verificar', [VotoController::class, 'verificarVoto']);

    // Escaneo de QR
    Route::post('/registrar-ingreso', [RegistroController::class, 'registrarIngreso']);
    Route::post('/registrar-egreso', [RegistroController::class, 'registrarEgreso']);

    // Ingreso y egreso
    Route::get('/ingreso', [RegistroController::class, 'getIngresos']);
    Route::get('/egreso', [RegistroController::class, 'getEgresos']);
    Route::get('/ingreso/exportar', [RegistroController::class, 'exportarIngresos']);
    Route::get('/egreso/exportar', [RegistroController::class, 'exportarEgresos']);
    Route::get('buscar-registro', [RegistroController::class, 'buscarRegistro']);

    // Auditoria
    Route::get('/auditoria/exportar', [AuditoriaController::class, 'exportarAuditoria']);
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->middleware('auth');

    // Usuarios
    Route::get('buscar-user', [UserController::class, 'buscarUser']);
    Route::apiResource('/user', UserController::class);
    Route::apiResource('/roles', RolesController::class);
    Route::apiResource('/seccionales', SeccionalController::class);

    // Ordenes Diarias
    Route::apiResource('/ordenes-diarias', OrdenDiariaController::class);
});
