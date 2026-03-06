<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validamos que los datos lleguen bien
        $validated = $request->validate([
            'nombre'   => 'required|string|max:255',
            'dni'      => 'required|string|unique:leads,dni', 
            'empresa'  => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email'    => 'nullable|email|max:255',
            
        ]);

        // 2. Guardamos en la base de datos usando el Modelo
        $lead = Lead::create($validated);

        // 3. Respondemos al Frontend
        return response()->json([
            'message' => 'Solicitud recibida correctamente',
            'data' => $lead
        ], 201);
    }
}