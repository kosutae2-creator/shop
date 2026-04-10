<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Prikazuje rezultate pretrage na posebnoj stranici.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Uzimamo pojam iz URL-a (npr. /search?q=patike)
        $query = $request->input('q');

        // Ako je pojam prazan, vraćamo praznu kolekciju umesto greške
        if (empty($query)) {
            return view('search.index', [
                'products' => collect(),
                'query' => ''
            ]);
        }

        /**
         * Pretraga proizvoda:
         * 1. Tražimo po nazivu (name)
         * 2. Tražimo po opisu (description) ako želiš širi opseg pretrage
         * 3. Koristimo paginate(12) da bismo imali npr. 12 proizvoda po strani
         */
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->latest() // Prvo prikazuje najnovije ubačene proizvode
            ->paginate(12)
            ->withQueryString(); // Ovo čuva "q=pojam" u linkovima za sledeću stranu

        return view('search.index', compact('products', 'query'));
    }
}