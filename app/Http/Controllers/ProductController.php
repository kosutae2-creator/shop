<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Prikaz detalja proizvoda
     */
    public function show($slug)
    {
        // 1. Povlačimo proizvod sa njegovom galerijom slika
        $product = Product::with('images')->where('slug', $slug)->firstOrFail();

        // 2. Izvlačimo povezane proizvode (nasumično, bez trenutnog)
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // 3. Povlačimo glavne kategorije sa decom za navigaciju
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        return view('product-details', compact('product', 'relatedProducts', 'categories'));
    }

    /**
     * NOVO: Prikaz proizvoda po kategoriji (Glavna ili Podkategorija)
     */
    public function category($slug)
    {
        // 1. Pronađi kategoriju, njenu decu i roditelja (za breadcrumbs)
        // withCount('products') nam omogućava da ispišemo npr. "Bušilice (12)"
        $category = Category::with(['children', 'parent'])->withCount('products')->where('slug', $slug)->firstOrFail();

        // 2. MAGIJA: Uzimamo ID ove kategorije + ID-ove svih njenih podkategorija
        // Ako klikneš na "Alati", videćeš i proizvode iz "Bušilice" i "Brusilice"
        $categoryIds = $category->children->pluck('id')->push($category->id);

        // 3. Povuci proizvode koji pripadaju bilo kom od tih ID-eva
        $products = Product::whereIn('category_id', $categoryIds)
            ->latest()
            ->get();

        // 4. Povuci glavne kategorije za meni (uvek nam trebaju za navigaciju)
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        // 5. Izdvajamo podkategorije za prikaz "čipova" (dugmića) na vrhu stranice
        $subcategories = $category->children;

        // Vraćamo isti 'welcome' view ili poseban view ako ga praviš
        return view('welcome', compact('category', 'products', 'subcategories', 'categories'));
    }
}