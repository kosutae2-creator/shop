<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Setting;
use Illuminate\Http\Request;

class ShopController extends Controller
{
  // ... unutar ShopController klase ...

public function index()
{
    $siteSettings = Setting::first();
    $categories = Category::all();
    
    // IZMENA: Uzimamo samo popust koji je tipa 'global' i koji je aktivan
    $activeDiscount = Discount::where('is_active', true)
                                ->where('type', 'global')
                                ->first();
    
    // featured products
    $featuredProducts = Product::where('status', 'active')
                                ->where('is_featured', true)
                                ->latest()
                                ->take(8)
                                ->get();

    $products = Product::where('status', 'active')
                        ->latest()
                        ->paginate(12);
    
    return view('welcome', compact(
        'products', 
        'categories', 
        'activeDiscount', 
        'siteSettings', 
        'featuredProducts'
    ));
}

public function category($slug)
{
    $category = Category::where('slug', $slug)->firstOrFail();
    $siteSettings = Setting::first(); 
    $categories = Category::all();

    // IZMENA: I ovde filtriramo da bude isključivo 'global'
    $activeDiscount = Discount::where('is_active', true)
                                ->where('type', 'global')
                                ->first();

    $query = Product::where('category_id', $category->id)
                    ->where('status', 'active');

    // ... ostatak sortiranja ostaje isti ...
    $sort = request('sort');
    if ($sort == 'price_low') { $query->orderBy('price', 'asc'); }
    elseif ($sort == 'price_high') { $query->orderBy('price', 'desc'); }
    elseif ($sort == 'name_az') { $query->orderBy('name', 'asc'); }
    else { $query->latest(); }

    $products = $query->paginate(12);
    $featuredProducts = Product::where('status', 'active')->where('is_featured', true)->take(4)->get();

    return view('welcome', compact(
        'products', 
        'categories', 
        'activeDiscount', 
        'category', 
        'siteSettings',
        'featuredProducts'
    ));
}
}