<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    // Función para que un socio consulte si está activo desde el front
    public function checkStatus($dni)
    {
        $member = Member::where('dni', $dni)->first();

        if (!$member) {
            return response()->json(['message' => 'No se encontró el DNI en el padrón'], 404);
        }

        return response()->json([
            'nombre' => $member->nombre,
            'estado' => $member->estado,
            'numero' => $member->numero_afiliado
        ]);
    }
}