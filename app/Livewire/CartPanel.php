<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Product;
use App\Models\Discount; // Dodajemo model za kupone

class CartPanel extends Component
{
    public $isOpen = false;
    public $cartItems = [];
    public $totalPrice = 0;
    
    // NOVE VARIJABLE ZA KUPON
    public $subtotal = 0;
    public $discountAmount = 0;
    public $couponCode = '';
    public $appliedDiscount = null;

    public function mount()
    {
        // Pri učitavanju proveravamo sesiju za kupon
        if (session()->has('applied_coupon')) {
            $this->appliedDiscount = session()->get('applied_coupon');
        }
        $this->loadCart();
    }

    #[On('addToCart')] 
    public function addToCart($productId, $quantity = 1, $options = [])
    {
        $product = Product::find($productId);
        if (!$product) return;

        $cart = session()->get('cart', []);
        $optionsKey = !empty($options) ? md5(json_encode($options)) : 'base';
        $cartKey = $product->id . '_' . $optionsKey;

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => (float)$product->price,
                "image" => $product->image,
                "options" => $options 
            ];
        }

        session()->put('cart', $cart);
        $this->loadCart();
        $this->isOpen = true; 
    }

    // NOVA FUNKCIJA ZA PRIMENU KUPONA
    public function applyCoupon()
    {
        $discount = Discount::where('code', $this->couponCode)
            ->where('is_active', true)
            ->where('type', 'coupon')
            ->first();

        if (!$discount) {
            session()->forget('applied_coupon');
            $this->appliedDiscount = null;
            session()->flash('coupon_error', 'KOD NIJE VALIDAN');
            $this->loadCart();
            return;
        }

        $couponData = [
            'id' => $discount->id,
            'code' => $discount->code,
            'percentage' => $discount->percentage
        ];

        session()->put('applied_coupon', $couponData);
        $this->appliedDiscount = $couponData;
        
        session()->flash('coupon_success', 'POPUST PRIMENJEN!');
        $this->loadCart();
    }

    // NOVA FUNKCIJA ZA UKLANJANJE KUPONA
    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        $this->appliedDiscount = null;
        $this->couponCode = '';
        $this->loadCart();
    }

    public function removeFromCart($key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }
        $this->loadCart();
    }

    public function incrementQuantity($key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['quantity']++;
            session()->put('cart', $cart);
        }
        $this->loadCart();
    }

    public function decrementQuantity($key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key]) && $cart[$key]['quantity'] > 1) {
            $cart[$key]['quantity']--;
            session()->put('cart', $cart);
        } elseif (isset($cart[$key]) && $cart[$key]['quantity'] == 1) {
            $this->removeFromCart($key);
        }
        $this->loadCart();
    }

    #[On('toggle-cart')]
    public function toggleCart()
    {
        $this->isOpen = !$this->isOpen;
        if($this->isOpen) $this->loadCart();
    }

    // IZMENJEN LOAD CART DA RAČUNA POPUST
    public function loadCart()
    {
        $this->cartItems = session()->get('cart', []);
        
        // 1. Osnovna cena
        $this->subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $this->cartItems));
        
        // 2. Računanje popusta
        $this->discountAmount = 0;
        if ($this->appliedDiscount) {
            $this->discountAmount = ($this->subtotal * $this->appliedDiscount['percentage']) / 100;
        }

        // 3. Finalna cena (totalPrice koji tvoj Blade već koristi)
        $this->totalPrice = $this->subtotal - $this->discountAmount;

        // Ako je korpa prazna, očisti kupon
        if (count($this->cartItems) === 0) {
            session()->forget('applied_coupon');
            $this->appliedDiscount = null;
        }
    }

    public function render()
    {
        return view('livewire.cart-panel');
    }
}