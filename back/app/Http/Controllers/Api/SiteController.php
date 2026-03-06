<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use App\Models\SalaryScale;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    public function getLandingData()
    {
        return response()->json([
            'news' => News::where('published', true)->latest()->take(3)->get(),
            'salaries' => SalaryScale::where('is_active', true)->orderBy('basic_salary', 'desc')->get(),
            'last_update' => SalaryScale::where('is_active', true)->max('effective_date'),
        ]);
    }
}