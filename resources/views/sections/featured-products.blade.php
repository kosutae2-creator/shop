{{-- resources/views/sections/featured-products.blade.php --}}

@if(isset($featuredProducts) && $featuredProducts->count() > 0)
<section class="py-12 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        
        {{-- Zaglavlje sekcije --}}
        <div class="flex justify-between items-end mb-8 md:mb-12">
            <div>
                <h2 class="text-2xl md:text-4xl font-black tracking-tighter uppercase italic">
                    Izdvajamo <span class="text-custom-primary">za vas</span>
                </h2>
                <div class="h-1 w-20 bg-custom-primary mt-2"></div>
            </div>
            
            <a href="/shop" class="hidden md:flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-custom-primary transition">
                Svi proizvodi
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>

        {{-- Grid sa tvojim karticama --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
            @foreach($featuredProducts as $product)
                {{-- Pozivamo tvoju komponentu iz components foldera --}}
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>

        {{-- Mobilno dugme "Vidi sve" --}}
        <div class="mt-10 md:hidden">
            <a href="/shop" class="block w-full text-center py-4 bg-slate-100 rounded-xl font-bold uppercase text-xs tracking-widest text-slate-600">
                Pogledaj celu ponudu
            </a>
        </div>
    </div>
</section>
@endif