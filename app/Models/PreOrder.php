<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreOrder extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_number',
        'customer_name',
        'year_level',
        'section',
        'status',
        'is_ready_for_pickup',
        'estimated_pickup_date',
        'total_amount',
        'notes',
        'cancellation_request_status',
        'cancellation_reason',
        'cancellation_requested_at',
    ];

    protected function casts(): array
    {
        return [
            'is_ready_for_pickup' => 'boolean',
            'estimated_pickup_date' => 'date',
            'total_amount' => 'decimal:2',
            'cancellation_requested_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PreOrderItem::class);
    }
}
