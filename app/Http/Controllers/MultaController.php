<?php

namespace App\Http\Controllers;

use App\Models\Multa;
use Illuminate\Http\Request;

class MultaController extends Controller
{
    public function index()
    {
        return Multa::latest()->get();
    }

    public function store(Request $request)
    {
        $multa = Multa::create($request->all());
        return response()->json($multa, 201);
    }

    // Devuelve la última multa (por id)
    public function ultimaMulta()
    {
        return Multa::orderByDesc('id')->first();
    }
}
