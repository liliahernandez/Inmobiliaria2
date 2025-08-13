<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MultaController;
use App\Http\Controllers\PasswordResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas de API para tu aplicación.
| Estas rutas son cargadas por el RouteServiceProvider y todas ellas
| serán asignadas al grupo de middleware "api".
|
*/

// --- RUTAS PÚBLICAS ---
Route::post('/login', [AuthController::class, 'login']);

// 🔑 Rutas de recuperación de contraseña (DEBEN SER PÚBLICAS)
Route::post('/password/forgot', [PasswordResetController::class, 'forgot']);
Route::post('/password/reset', [PasswordResetController::class, 'reset']);

// --- RUTAS PROTEGIDAS ---
Route::middleware('auth:sanctum')->group(function () {

    // Rutas de autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 💬 Cambio de contraseña + revocación de sesiones
    Route::post('/cambiar-password', [UserController::class, 'cambiarPassword']);

    // ✅ Verificación de sesión activa para Vue Router
    Route::get('/check-session', function (Request $request) {
        return response()->json(['status' => 'active']);
    });

    // --- MULTAS ---
    Route::get('/multas', [MultaController::class, 'index']);
    Route::post('/multas', [MultaController::class, 'store']);
    Route::get('/ultima-multa', [MultaController::class, 'ultimaMulta']);

    // --- ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Bienvenido al dashboard de Admin']);
        });
        // ... otras rutas de admin
    });

    // --- EDITOR ---
    Route::middleware(['role:editor'])->prefix('editor')->group(function () {
        Route::get('/articles', function () {
            return response()->json(['message' => 'Aquí puedes gestionar artículos']);
        });
        // ... otras rutas de editor
    });

    // --- Acceso compartido por roles ---
    Route::middleware(['role:admin|editor'])->get('/shared-content', function () {
        return response()->json(['message' => 'Contenido para admins y editores']);
    });

});
