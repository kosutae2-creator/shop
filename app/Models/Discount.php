<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'percentage',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime', // Veoma bitno za rad sa datumima
        'percentage' => 'integer',
        'min_order_amount' => 'decimal:2',
    ];

    /**
     * Pomoćna funkcija: Proverava da li je kupon trenutno validan.
     */
    public function isValid($totalAmount = 0)
    {
        // 1. Proveri da li je uopšte aktivan
        if (!$this->is_active) return false;

        // 2. Proveri datum isteka
        if ($this->expires_at && $this->expires_at->isPast()) return false;

        // 3. Proveri limit korišćenja
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        // 4. Proveri minimalni iznos korpe
        if ($this->min_order_amount && $totalAmount < $this->min_order_amount) return false;

        return true;
    }
}