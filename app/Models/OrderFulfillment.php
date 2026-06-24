<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderFulfillment extends Model
{
    protected $fillable = [
        'order_id',
        'umkm_id',
        'status',
        'courier',
        'tracking_number',
        'notes',
        'packed_at',
        'shipped_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'packed_at' => 'datetime',
            'shipped_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class);
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isPacked(): bool
    {
        return $this->status === 'packed';
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBePacked(): bool
    {
        return $this->status === 'processing';
    }

    public function canBeShipped(): bool
    {
        return $this->status === 'packed';
    }

    public function canBeCompleted(): bool
    {
        return $this->status === 'shipped';
    }
}
