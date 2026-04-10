<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Discount;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\SearchController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. POČETNA STRANA
Route::get('/', [ShopController::class, 'index'])->name('shop.index');

// 2. DETALJI PROIZVODA
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// 3. KATEGORIJE 
Route::get('/kategorija/{slug}', [ShopController::class, 'category'])->name('category.show');

// 4. KORPA (Tradicionalne rute ako zatrebaju, mada Livewire sada radi većinu posla)
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/store', [CartController::class, 'store'])->name('cart.store');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
});

// 5. CHECKOUT (Izmenjeno ime rute u 'checkout' da bi se poklapalo sa Livewire korpom)
Route::get('/checkout', function () {
    $cart = session()->get('cart', []);
    
    if(empty($cart)) {
        return redirect()->route('shop.index');
    }

    $subtotal = 0;
    foreach($cart as $details) {
        $subtotal += (float)$details['price'] * (int)$details['quantity'];
    }

    $globalDiscount = Discount::where('is_active', true)->first();
    $discountAmount = 0;
    
    if ($globalDiscount && $subtotal > 0) {
        $discountAmount = ($subtotal * $globalDiscount->percentage) / 100;
    }

    $total = $subtotal - $discountAmount;

    return view('checkout', compact('cart', 'subtotal', 'globalDiscount', 'discountAmount', 'total'));
})->name('checkout');

// 6. PORUDŽBINE
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');

// 7. STRANICA ZA ZAHVALNOST
Route::get('/thanks/{order}', function (Order $order) {
    return view('thanks', compact('order'));
})->name('thanks');

// 8. POMOĆNE RUTE
Route::get('/setup-storage', function () {
    Artisan::call('storage:link');
    return "Slike su povezane!";
});

Route::get('/reset', function() {
    session()->flush();
    return "Sesija je obrisana! Vrati se na shop i dodaj artikal ponovo.";
});

// 9. QUICK VIEW
Route::get('/api/products/{id}', function($id) {
    return App\Models\Product::findOrFail($id);
});

// 10. SEARCH
Route::get('/search', [SearchController::class, 'index'])->name('search.index');