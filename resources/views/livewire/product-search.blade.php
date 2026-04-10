<div x-data="{ open: false }" class="relative flex items-center">
    
    <!-- Dugme: Lupa -->
    <button @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())" 
            class="p-2.5 text-slate-500 hover:text-custom-primary hover:bg-slate-50 rounded-xl transition-all duration-300">
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Dropdown prozor za pretragu -->
    <div x-show="open" 
         @click.away="open = false"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="absolute right-0 top-full mt-4 w-[320px] md:w-[450px] bg-white shadow-[0_20px_50px_rgba(0,0,0,0.15)] rounded-2xl border border-slate-100 overflow-hidden z-[100]">
        
        <div class="p-4 bg-slate-50/50">
            <div class="relative">
                <input 
                    x-ref="searchInput"
                    wire:model.live.debounce.300ms="query"
                    type="text" 
                    placeholder="Šta tražite danas?" 
                    class="w-full bg-white border-none rounded-xl px-11 py-3 text-sm shadow-sm focus:ring-2 focus:ring-custom-primary transition-all uppercase tracking-tight font-medium text-slate-700"
                >
                <svg class="absolute left-4 top-3.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>

                <!-- Loading Spinner -->
                <div wire:loading class="absolute right-4 top-3.5">
                    <svg class="animate-spin h-4 w-4 text-custom-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rezultati -->
        <div class="max-h-[350px] overflow-y-auto p-2">
            @if(strlen($query) < 2)
                <div class="p-8 text-center">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 italic">Unesite bar 2 karaktera...</p>
                </div>
            @elseif(count($results) > 0)
                <div class="grid gap-1">
                    @foreach($results as $product)
                        <a href="{{ route('product.show', $product->slug) }}" class="flex items-center gap-4 p-2.5 hover:bg-slate-50 rounded-xl transition-all group">
                            <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 border border-slate-100 shrink-0">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/100' }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <h4 class="text-[11px] font-black text-slate-800 uppercase italic truncate leading-none">{{ $product->name }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-custom-primary">{{ number_format($product->price, 0, ',', '.') }} RSD</span>
                                    <span class="text-[9px] text-slate-400 uppercase tracking-tighter">Detaljnije →</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Nema rezultata</p>
                </div>
            @endif
        </div>

        @if(count($results) > 0)
            <div class="p-3 bg-slate-50 border-t border-slate-100">
                {{-- IZMENJENO: Sada je običan link koji vodi na stranicu sa svim rezultatima --}}
                <a href="/search?q={{ $query }}" class="block w-full py-2 text-[10px] font-black uppercase tracking-widest text-center text-slate-500 hover:text-custom-primary transition-colors">
                    Pogledaj sve rezultate
                </a>
            </div>
        @endif
    </div>
</div>