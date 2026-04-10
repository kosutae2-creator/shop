<x-layouts.app>
    {{-- 
        GLAVNI KONTEJNER: 
        Sve stavljamo unutar x-data da bi Quick View modal mogao da radi,
        i dodajemo pt-32/40 da navigacija ne prekriva naslov.
    --}}
    <div x-data="{ quickViewOpen: false, activeProduct: null }" class="relative pt-32 md:pt-40 bg-white min-h-screen">
        
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            
            {{-- 1. NASLOV PRETRAGE --}}
            <div class="mb-12 border-b border-zinc-100 pb-10">
                <nav class="flex mb-4 uppercase tracking-[0.3em] text-[10px] font-black text-zinc-400">
                    <a href="/" class="hover:text-rose-600 transition-colors">Početna</a>
                    <span class="mx-3 text-zinc-200">/</span>
                    <span class="text-zinc-900 underline decoration-rose-600 decoration-2 underline-offset-4 italic">Pretraga</span>
                </nav>

                <h1 class="text-5xl md:text-7xl font-black text-zinc-900 uppercase italic tracking-tighter leading-none">
                    Rezultati <span class="text-rose-600">pretrage.</span>
                </h1>
                
                <p class="mt-4 text-zinc-400 uppercase text-[10px] tracking-[0.2em] font-black italic">
                    Pronađeno je {{ $products->total() }} proizvoda za vaš upit
                </p>
            </div>

            {{-- 2. GRID PROIZVODA --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-10 pb-20">
                @forelse($products as $product)
                    {{-- 
                        UKLJUČUJEMO TVOJU SREĐENU KARTICU:
                        Koristimo isti partial kao u welcome.blade.php 
                    --}}
                    @include('layouts.partials.product-card', ['product' => $product])
                @empty
                    {{-- STANJE KADA NEMA REZULTATA --}}
                    <div class="col-span-full py-32 text-center">
                        <div class="inline-block p-10 rounded-full bg-zinc-50 mb-6">
                            <svg class="w-16 h-16 text-zinc-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black uppercase italic tracking-tighter text-zinc-400">Nismo pronašli ništa pod tim nazivom</h3>
                        <p class="text-zinc-400 text-sm mt-2">Pokušajte sa nekim drugim terminom ili se vratite u shop.</p>
                        <a href="/shop" class="mt-8 inline-block bg-zinc-900 text-white px-8 py-4 rounded-full font-black uppercase italic tracking-widest text-[10px] hover:bg-rose-600 transition-all">Prikaži sve proizvode</a>
                    </div>
                @endforelse
            </div>

            {{-- 3. PAGINACIJA --}}
            @if($products->hasPages())
                <div class="mt-12 pb-20 flex justify-center border-t border-zinc-50 pt-10">
                    {{ $products->links() }}
                </div>
            @endif

        </div>

        {{-- 4. UKLJUČUJEMO QUICK VIEW MODAL --}}
        @include('layouts.partials.quick-view-modal')

    </div> {{-- KRAJ X-DATA --}}
</x-layouts.app>