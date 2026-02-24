<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    // Listado de noticias publicadas
    public function index()
    {
        return News::where('published', true)
            ->latest()
            ->get();
    }

    // Ver una noticia
    public function show($id)
    {
        return News::where('published', true)
            ->findOrFail($id);
    }
}