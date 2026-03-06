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
    // Buscamos la noticia por su ID
    $news = \App\Models\News::find($id);

    // Si no existe, devolvemos un error 404
    if (!$news) {
        return response()->json(['message' => 'Noticia no encontrada'], 404);
    }

    // Si existe, devolvemos el JSON de esa noticia
    return response()->json($news);
}
}