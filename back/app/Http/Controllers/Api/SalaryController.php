<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalaryScale;
use Illuminate\Http\JsonResponse;

class SalaryController extends Controller
{
    public function index(): JsonResponse
    {
        // Escalas ordenadas por categoría
        $salaries = SalaryScale::orderBy('category', 'asc')->get();
        
        return response()->json($salaries);
    }
}