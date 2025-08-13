<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Todavía se usa Str para el email, pero no para el token de recuperación
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    // Método para solicitar la recuperación de contraseña
    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'No encontramos un usuario con ese correo.'], 404);
        }

        // Generar un token numérico de 6 dígitos
        $token = strval(random_int(100000, 999999)); // Genera un número aleatorio entre 100000 y 999999

        // Eliminar tokens anteriores para el mismo usuario
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Almacenar el token en la tabla password_resets
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Enviar el correo electrónico con el nuevo token numérico
        Mail::to($user->email)->send(new PasswordResetMail($token));

        return response()->json(['message' => 'El código de recuperación ha sido enviado a tu correo.']);
    }

    // Método para resetear la contraseña
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $passwordReset = DB::table('password_resets')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        // Si el token no existe o ya expiró (¡Ajustamos el tiempo de expiración a 10 minutos!)
        if (!$passwordReset || now()->diffInMinutes($passwordReset->created_at) > 10) { // <-- ¡IMPORTANTE CAMBIO AQUÍ!
            return response()->json(['message' => 'El código no es válido o ha expirado.'], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'No encontramos un usuario con ese correo.'], 404);
        }

        // Actualizar la contraseña
        $user->password = Hash::make($request->password);
        $user->save();

        // Eliminar el token de la base de datos para que no pueda ser usado de nuevo
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Tu contraseña ha sido actualizada con éxito.']);
    }
}
