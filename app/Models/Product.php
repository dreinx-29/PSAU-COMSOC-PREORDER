<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category',
        'image',
        'is_available',
        'is_clothing',
        'available_sizes',
        'size_chart',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_available' => 'boolean',
            'is_clothing' => 'boolean',
            'available_sizes' => 'array',
        ];
    }
}