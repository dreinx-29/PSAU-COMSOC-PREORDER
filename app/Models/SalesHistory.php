<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesHistory extends Model
{
    protected $fillable = [
        'total_sales',
        'reset_at',
    ];

    protected $casts = [
        'total_sales' => 'decimal:2',
        'reset_at' => 'datetime',
    ];
}
