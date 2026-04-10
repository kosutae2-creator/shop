<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'price', 
        'old_price', 
        'image', 
        'stock', 
        'status',      // Neophodno za admin
        'is_featured',  // Neophodno za početnu stranu
        'variants',
        'options',
        'category_id',
    ];

    protected $casts = [
        'variants'    => 'array',
        'options'     => 'array',
        'is_featured' => 'boolean', // Pretvara 0/1 iz baze u true/false za Filament
        'price'       => 'double',
        'old_price'   => 'double',
        'category_id' => 'integer',
    ];

    /**
     * Relacija sa kategorijom (Pripada jednoj kategoriji)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relacija sa dodatnim slikama (Galerija)
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Proverava da li proizvod ima popust
     */
    public function hasDiscount(): bool
    {
        return $this->old_price && $this->old_price > $this->price;
    }

    /**
     * Vraća procenat uštede za kupca
     */
    public function discountPercentage(): int
    {
        if (!$this->hasDiscount()) return 0;
        
        return (int) round((($this->old_price - $this->price) / $this->old_price) * 100);
    }
}