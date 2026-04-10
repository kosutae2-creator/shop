<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class CartCount extends Component
{
    public $count = 0;

    public function mount() 
    { 
        $this->updateCount(); 
    }

    // Slušamo addToCart (iz welcome stranice) i cartUpdated (iz samog panela)
    #[On('addToCart')]
    #[On('cartUpdated')]
    #[On('toggle-cart')]
    public function updateCount()
    {
        $cart = session()->get('cart', []);
        
        // Sabiramo sve quantity vrednosti da bi broj bio tačan (npr. 2 artikla + 3 artikla = 5)
        $totalQuantity = 0;
        if (is_array($cart)) {
            foreach ($cart as $item) {
                $totalQuantity += $item['quantity'] ?? 0;
            }
        }
        
        $this->count = $totalQuantity;
    }

    public function render()
    {
        return <<<'blade'
            <span class="absolute -top-1 -right-1 bg-custom-primary text-white text-[10px] font-bold h-5 w-5 flex items-center justify-center rounded-full border-2 border-white">
                {{ $count }}
            </span>
        blade;
    }
}