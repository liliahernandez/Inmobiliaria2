<?php

namespace App\Http\Controllers;

use App\Models\Multa;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // Necesario para capturar errores de validación

class MultaController extends Controller
{
    /**
     * Obtiene todas las multas ordenadas por fecha de creación descendente.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Devuelve las multas más recientes primero
        return Multa::latest()->get();
    }

    /**
     * Almacena una nueva multa en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // 1. Validar los datos de entrada
            // Aquí definimos qué campos son requeridos y qué formato deben tener.
            // Si algún campo 'required' no viene o no cumple el formato, Laravel lanzará un ValidationException.
            $validatedData = $request->validate([
                'nombre_residente' => 'required|string|max:255',
                'motivo' => 'required|string|max:255',
                'monto' => 'required|numeric|min:0', // 'monto' debe ser un número y mayor o igual a 0
                'fecha' => 'required|date',         // 'fecha' debe ser una fecha válida
            ]);

            // 2. Crear una nueva instancia de Multa usando los datos validados
            // Esto asegura que solo los datos que pasaron la validación y están en $fillable
            // del modelo Multa serán usados para la creación.
            $multa = Multa::create($validatedData);

            // 3. Devolver una respuesta exitosa
            return response()->json($multa, 201); // 201 Created

        } catch (ValidationException $e) {
            // Captura errores específicos de validación (por ejemplo, si falta un campo requerido).
            // Devuelve un código de estado 422 (Unprocessable Entity) con los detalles del error.
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Captura cualquier otro error inesperado que pueda ocurrir al guardar en la base de datos.
            // Devuelve un código de estado 500 (Internal Server Error).
            // $e->getMessage() puede ser útil para depurar en desarrollo.
            return response()->json(['message' => 'Ocurrió un error inesperado al guardar la multa.', 'error_detail' => $e->getMessage()], 500);
        }
    }

    /**
     * Devuelve la última multa (por id) registrada.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ultimaMulta()
    {
        // Obtiene la última multa basándose en el ID descendente
        $ultimaMulta = Multa::orderByDesc('id')->first();
        
        if ($ultimaMulta) {
            return response()->json($ultimaMulta);
        }
        
        // Si no hay multas, devuelve un 404
        return response()->json(['message' => 'No hay multas registradas.'], 404);
    }
}
