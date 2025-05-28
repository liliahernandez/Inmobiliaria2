<?php

namespace App\Http\Controllers;

// app/Http/Controllers/MultaController.php
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
}

