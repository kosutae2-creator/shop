{{-- featured-products.blade.php --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        
        {{-- Naslov sekcije --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
            <div>
                <span class="text-custom-primary font-bold uppercase tracking-widest text-xs">Naša preporuka</span>
                <h2 class="text-3xl md:text-4xl font-black tracking-tighter uppercase italic mt-1">
                    Izdvajamo <span class="text-slate-900 underline decoration-custom-primary decoration-4 underline-offset-8">za vas</span>
                </h2>
            </div>
            
            <a href="/shop" class="group flex items-center gap-2 text-sm font-bold uppercase tracking-widest hover:text-custom-primary transition">
                Vidi kompletnu ponudu
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        {{-- Grid sa proizvodima --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            {{-- Ovde koristimo @forelse u slučaju da nema proizvoda --}}
            @forelse($featuredProducts as $product)
                @include('components.product-card', ['product' => $product])
            @empty
                <div class="col-span-full text-center py-20 bg-slate-50 rounded-3xl">
                    <p class="text-slate-400 font-medium italic">Trenutno nema istaknutih proizvoda.</p>
                </div>
            @endforelse
        </div>

    </div>
</section>