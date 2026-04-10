<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Discount;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        // 🧹 OČISTI STARE STAVKE (ukloni quantity iz options i popravi tipove)
        foreach ($cart as $key => $item) {

            if(isset($item['options'])) {
                unset($cart[$key]['options']['quantity']);
                unset($cart[$key]['options']['kolicina']);
                unset($cart[$key]['options']['qty']);
            }

            if (!isset($item['quantity']) || $item['quantity'] < 1) {
                $cart[$key]['quantity'] = 1;
            }

            $cart[$key]['price'] = (float) $cart[$key]['price'];
            $cart[$key]['quantity'] = (int) $cart[$key]['quantity'];
        }

        session()->put('cart', $cart);

        // 💰 SUBTOTAL
        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += (float)$details['price'] * (int)$details['quantity'];
        }

        // 🎯 POPUST
        $globalDiscount = Discount::where('is_active', true)->first();
        $discountAmount = 0;

        if ($globalDiscount && $subtotal > 0) {
            $discountAmount = ($subtotal * (float)$globalDiscount->percentage) / 100;
        }

        $total = $subtotal - $discountAmount;

        return view('cart', compact('cart', 'subtotal', 'globalDiscount', 'discountAmount', 'total'));
    }

    public function store(Request $request)
{
    $product = Product::findOrFail($request->id);
    $options = $request->input('options', []);

    // Čišćenje opcija
    unset($options['quantity'], $options['kolicina'], $options['qty']);

    $requestedQuantity = max(1, (int)$request->input('quantity', 1));

    // Unikatan ključ (ID + MD5 opcija) - isto kao u Livewire-u
    $optionsKey = !empty($options) ? md5(json_encode($options)) : 'base';
    $cartKey = $product->id . '_' . $optionsKey;

    $cart = session()->get('cart', []);

    if(isset($cart[$cartKey])) {
        $cart[$cartKey]['quantity'] += $requestedQuantity;
    } else {
        $cart[$cartKey] = [
            "id" => $product->id,
            "name" => $product->name, // Ime ostaje čisto
            "quantity" => $requestedQuantity,
            "price" => (float)$product->price,
            "image" => $product->image,
            "options" => $options // Ovde idu sirovi podaci: ['Veličina' => 'L', 'Boja' => 'Plava']
        ];
    }

    session()->put('cart', $cart);
    return redirect()->route('cart.index')->with('success', 'Dodato u korpu!');
}

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$request->id])) {
            $newQty = max(1, (int)$request->input('quantity', 1));
            $cart[$request->id]['quantity'] = $newQty;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Količina ažurirana!');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return back();
    }
}
