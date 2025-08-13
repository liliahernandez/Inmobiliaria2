<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'nueva_password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($request->password_actual, $user->password)) {
            return response()->json([
                'message' => 'La contraseña actual no es correcta.'
            ], 422);
        }

        $user->password = Hash::make($request->nueva_password);
        $user->save();

        // 💥 Eliminar todos los tokens de acceso (cerrar sesión en todos los dispositivos)
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Contraseña actualizada. Se cerraron todas las sesiones activas.'
        ]);
    }
}
