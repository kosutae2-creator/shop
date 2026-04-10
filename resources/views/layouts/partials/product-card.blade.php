<div class="group relative bg-white border border-gray-200 rounded-lg flex flex-col overflow-hidden shadow-sm hover:shadow-md transition">
    
    <!-- 1. BADGE ZA AKCIJU -->
    @if($product->old_price && $product->old_price > $product->price)
        <div class="absolute top-2 left-2 z-20">
            <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm uppercase tracking-wider">
                AKCIJA -{{ round(100 - ($product->price / $product->old_price * 100)) }}%
            </span>
        </div>
    @endif

    <!-- 2. SLIKA I BRZI PREGLED OVERLAY -->
    <div class="relative aspect-[3/4] bg-gray-100 overflow-hidden">
        <img src="{{ asset('storage/' . $product->image) }}" 
             alt="{{ $product->name }}" 
             class="w-full h-full object-center object-cover group-hover:scale-105 transition-transform duration-500">
        
        <!-- Overlay koji šalje SVE podatke modalu -->
        <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center z-10">
         <!-- Dugme unutar product-card.blade.php -->
<button 
    type="button"
    @click="activeProduct = { 
        id: {{ $product->id }}, 
        name: '{{ addslashes($product->name) }}', 
        price: {{ $product->price }}, 
        image: '{{ $product->image }}',
        description: '{{ addslashes($product->description) }}'
    }; quickViewOpen = true"
    class="bg-white text-black px-5 py-2.5 rounded-full text-[11px] font-bold shadow-xl hover:bg-black hover:text-white transition transform translate-y-4 group-hover:translate-y-0 duration-300 uppercase tracking-tighter">
    Brzi pregled
</button>
        </div>
    </div>

    <!-- 3. INFORMACIJE -->
    <div class="flex-1 p-4 flex flex-col">
        <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] mb-1">
            {{ $product->category->name ?? 'Kategorija' }}
        </p>

        <h3 class="text-sm font-semibold text-gray-900 mb-2">
            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-custom-primary transition">
                {{ $product->name }}
            </a>
        </h3>
        
        <div class="mt-auto pt-2">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-base font-black text-gray-900">
                    {{ number_format($product->price, 0, ',', '.') }} <small class="text-[10px]">RSD</small>
                </span>
                @if($product->old_price)
                    <span class="text-xs text-gray-400 line-through">
                        {{ number_format($product->old_price, 0, ',', '.') }}
                    </span>
                @endif
            </div>
            
            @if($product->stock > 0)
                <button 
                    type="button"
                    @click="$dispatch('addToCart', { productId: {{ $product->id }} })" 
                    class="w-full bg-slate-900 text-white py-3 rounded-xl text-[11px] font-black uppercase tracking-widest italic hover:bg-custom-primary transition-all flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Dodaj u korpu</span>
                </button>
            @else
                <button disabled class="w-full bg-gray-100 text-gray-400 py-3 rounded-xl text-[11px] font-bold uppercase tracking-widest cursor-not-allowed text-center">
                    Nema na stanju
                </button>
            @endif
        </div>
    </div>
</div>