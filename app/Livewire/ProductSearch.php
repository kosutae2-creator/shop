<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product; // Ovo je ključna linija koja je falila

class ProductSearch extends Component
{
    public $query = '';
    public $results = [];

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        // Sada će model biti pravilno učitan
        $this->results = Product::where('name', 'like', '%' . $this->query . '%')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.product-search');
    }
    
    
    public function updatedSearch()
{
    // Ako želiš da odmah prebaci na search stranicu čim krene kucanje
    return redirect()->to('/search?q=' . $this->search);
}
    
}