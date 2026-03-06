<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryScale extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'basic_salary',
        'non_remunerative',
        'effective_date',
        'is_active'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'is_active' => 'boolean',
    ];
}