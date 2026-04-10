<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name', 
        'customer_email',
        'phone', 
        'address', 
        'subtotal', 
        'discount_amount', 
        'total_amount', 
        'coupon_code', // DODATO: Da bi Laravel dozvolio upis koda
        'items', 
        'status'
    ];

    // Automatski pretvara 'items' iz niza u JSON i obrnuto
    protected $casts = [
        'items' => 'array',
    ];
}