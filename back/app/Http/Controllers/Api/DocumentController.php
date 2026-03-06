<?php

namespace App\Http\Controllers\Api;
namespace App\Http\Controllers\Api; 
use App\Http\Controllers\Controller;
use App\Models\Document;


class DocumentController extends Controller
{
    public function index()
    {
        // Documentos ordenados por fecha
        return response()->json(Document::latest()->get());
    }
}