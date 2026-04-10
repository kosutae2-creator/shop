<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Discount;
use Illuminate\Support\Facades\Mail; 
use App\Mail\OrderReceived;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validacija
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string',
            'address' => 'required|string',
            'city'    => 'required|string',
        ]);

        // 2. Provera korpe
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Korpa je prazna.');
        }

        // 3. Kalkulacija osnovne cene (Subtotal)
        $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        // 4. Kalkulacija popusta (LOGIKA PRIORITETA)
        $totalDiscountAmount = 0;
        $appliedCouponCode = null;

        // PRVO: Proveravamo da li postoji kupon u sesiji
        if (session()->has('applied_coupon')) {
            $couponSession = session()->get('applied_coupon');
            $coupon = Discount::where('code', $couponSession['code'])
                               ->where('is_active', true)
                               ->where('type', 'coupon')
                               ->first();

            if ($coupon && $coupon->isValid($subtotal)) {
                $totalDiscountAmount = ($subtotal * $coupon->percentage) / 100;
                $appliedCouponCode = $coupon->code;
                
                // Povećavamo broj korišćenja
                $coupon->increment('used_count');
            }
        } 
        
        // DRUGO: Ako NIJE primenjen kupon (iznos popusta je i dalje 0), proveri globalni popust
        if ($totalDiscountAmount == 0) {
            $globalDiscount = Discount::where('is_active', true)
                                      ->where('type', 'global')
                                      ->first();
                                      
            if ($globalDiscount && $globalDiscount->isValid($subtotal)) {
                $totalDiscountAmount = ($subtotal * $globalDiscount->percentage) / 100;
            }
        }

        $finalTotal = $subtotal - $totalDiscountAmount;

        // 5. Upis u bazu podataka
        try {
            $order = Order::create([
                'customer_name'   => $validated['name'],
                'customer_email'  => $validated['email'],
                'phone'           => $validated['phone'],
                'address'         => $validated['address'] . ', ' . $validated['city'],
                'subtotal'        => $subtotal,
                'discount_amount' => $totalDiscountAmount,
                'total_amount'    => $finalTotal,
                'items'           => $cart,
                'status'          => 'novo',
                'coupon_code'     => $appliedCouponCode,
            ]);

            // 6. Slanje emailova
            // Mail::to($validated['email'])->send(new OrderReceived($order));
            // Mail::to('kosutae2@gmail.com')->send(new OrderReceived($order)); 

            // 7. Čišćenje sesije
            session()->forget(['cart', 'applied_coupon']);

            return redirect()->route('thanks', ['order' => $order->id])
                             ->with('success', 'Porudžbina je uspešno primljena!');

        } catch (\Exception $e) {
            \Log::error("Order Store Error: " . $e->getMessage());
            return back()->with('error', 'Došlo je do greške prilikom obrade porudžbine.');
        }
    }
}