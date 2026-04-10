<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // DODATO: Za povezivanje sa proizvodima
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Relacija: Jedna kategorija ima mnogo proizvoda.
     * Ovo omogućava da pozoveš $category->products i dobiješ listu svih proizvoda u toj kategoriji.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    
    
    
    public function parent()
{
    return $this->belongsTo(Category::class, 'parent_id');
}

public function children()
{
    return $this->hasMany(Category::class, 'parent_id');
}
    
    
    
    
    
    
    
    
}