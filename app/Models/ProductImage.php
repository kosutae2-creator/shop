<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    // Dozvoljavamo masovni upis za ove kolone
    protected $fillable = ['product_id', 'image_path'];

    /**
     * Relacija nazad ka proizvodu
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}