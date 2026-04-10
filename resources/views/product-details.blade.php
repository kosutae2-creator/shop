<x-layouts.app>
    {{-- SEO Dinamički podaci --}}
    @section('title', $product->name . ' | ' . ($siteSettings->site_name ?? 'ProShop'))

    <div x-data="{ 
        quantity: 1, 
        selectedOptions: {},
        addToCart() {
            // Provera da li su izabrane sve dostupne varijante
            const requiredGroups = document.querySelectorAll('.option-group').length;
            const selectedCount = Object.keys(this.selectedOptions).length;

            if (selectedCount < requiredGroups) {
                alert('Molimo vas da izaberete sve opcije (veličinu, boju i sl.) pre dodavanja u korpu.');
                return;
            }

            // KLJUČNA ISPRAVKA: Šaljemo parametre odvojeno da bi ih PHP prihvatio bez BindingResolutionException greške
            $dispatch('addToCart', { 
                productId: {{ $product->id }}, 
                quantity: this.quantity,
                options: this.selectedOptions
            });
        }
    }" class="bg-white">
    
<main class="max-w-7xl mx-auto px-4 pt-32 pb-10 md:pt-40 md:pb-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                
                {{-- LEVA KOLONA: GALERIJA --}}
                <div class="space-y-6">
                    <div class="relative aspect-square bg-white rounded-[32px] md:rounded-[48px] overflow-hidden border border-slate-100 shadow-sm group">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/800x800?text=No+Image' }}" 
                             id="mainImage" 
                             class="w-full h-full object-contain p-8 transition-transform duration-700 group-hover:scale-105">
                        
                        @if($product->old_price > $product->price)
                            <div class="absolute top-6 left-6 bg-red-500 text-white px-4 py-1.5 rounded-full font-black text-[10px] uppercase italic tracking-widest shadow-lg z-10">
                                AKCIJA
                            </div>
                        @endif
                    </div>

                    @if($product->images && $product->images->count() > 0)
                    <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
                        <button onclick="changeImage('{{ asset('storage/' . $product->image) }}', this)" 
                                class="thumb-btn w-20 h-20 rounded-2xl border-2 border-custom-primary bg-white p-2 flex-shrink-0 transition-all">
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-contain">
                        </button>
                        @foreach($product->images as $extra)
                        <button onclick="changeImage('{{ asset('storage/' . $extra->image_path) }}', this)" 
                                class="thumb-btn w-20 h-20 rounded-2xl border-2 border-transparent bg-white p-2 flex-shrink-0 opacity-60 hover:opacity-100 transition-all">
                             <img src="{{ asset('storage/' . $extra->image_path) }}" class="w-full h-full object-contain">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- DESNA KOLONA: INFO --}}
                <div class="flex flex-col">
                    <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                        <a href="/" class="hover:text-custom-primary transition-colors">Shop</a>
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="text-slate-900 font-bold uppercase">{{ $product->name }}</span>
                    </nav>

                    <h1 class="text-4xl md:text-6xl font-black tracking-tighter italic mb-6 uppercase leading-none text-slate-900">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-center gap-4 mb-8">
                        <span class="text-4xl font-black text-custom-primary italic tracking-tighter">
                            {{ number_format($product->price, 0, ',', '.') }} <span class="text-lg font-bold">RSD</span>
                        </span>
                        @if($product->old_price)
                            <span class="text-xl text-slate-300 line-through font-bold">
                                {{ number_format($product->old_price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>

                    <div class="prose prose-slate mb-10">
                        <p class="text-slate-500 leading-relaxed text-lg font-medium italic">
                            {!! nl2br(e($product->description)) !!}
                        </p>
                    </div>

                    {{-- VARIJACIJE --}}
                    @php
                        $variants = $product->variants ?? [];
                        $sizes = $variants['sizes'] ?? [];
                        $colors = $variants['colors'] ?? [];
                    @endphp

                    <div class="space-y-8 mb-10">
                        {{-- VELIČINE --}}
                        @if(!empty($sizes))
                            <div class="option-group">
                                <h4 class="text-[10px] font-black uppercase text-slate-400 mb-4 tracking-[0.2em] ml-1">Veličina</h4>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($sizes as $size)
                                        <button 
                                            type="button"
                                            @click="selectedOptions['Veličina'] = '{{ $size }}'"
                                            :class="selectedOptions['Veličina'] === '{{ $size }}' 
                                                ? 'border-custom-primary text-custom-primary bg-custom-primary/5 shadow-sm' 
                                                : 'border-slate-100 text-slate-600 bg-white hover:border-slate-300'"
                                            class="px-6 py-3 rounded-2xl border-2 text-[11px] font-black uppercase tracking-widest transition-all active:scale-95">
                                            {{ $size }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- BOJE --}}
                        @if(!empty($colors))
                            <div class="option-group">
                                <h4 class="text-[10px] font-black uppercase text-slate-400 mb-4 tracking-[0.2em] ml-1">Boja</h4>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($colors as $color)
                                        <button 
                                            type="button"
                                            @click="selectedOptions['Boja'] = '{{ $color }}'"
                                            :class="selectedOptions['Boja'] === '{{ $color }}' 
                                                ? 'border-custom-primary text-custom-primary bg-custom-primary/5 shadow-sm' 
                                                : 'border-slate-100 text-slate-600 bg-white hover:border-slate-300'"
                                            class="px-6 py-3 rounded-2xl border-2 text-[11px] font-black uppercase tracking-widest transition-all active:scale-95">
                                            {{ $color }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    {{-- TEHNIČKE KARAKTERISTIKE --}}
                    @if(!empty($product->options) && is_array($product->options))
                    <div class="mb-10 p-6 bg-slate-50 rounded-[32px] border border-slate-100">
                        <h4 class="text-[10px] font-black uppercase text-slate-900 mb-6 tracking-[0.2em]">Specifikacije</h4>
                        <div class="space-y-4">
                            @foreach($product->options as $property => $value)
                                <div class="flex justify-between items-start py-2 border-b border-slate-200/50 last:border-0 gap-4">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $property }}</span>
                                    <span class="text-[11px] font-black text-slate-700 uppercase italic text-right leading-tight">
                                        {{ $value }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- KUPOVINA --}}
                    <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-xl shadow-slate-100/50">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-6">
                            <div class="flex-1">
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest ml-1">Količina</label>
                                <div class="flex items-center bg-slate-50 rounded-2xl p-1.5 border border-slate-100">
                                    <button @click="if(quantity > 1) quantity--" class="w-12 h-12 flex items-center justify-center font-black text-slate-400 hover:text-custom-primary text-xl">-</button>
                                    <input type="number" x-model="quantity" readonly class="w-full bg-transparent text-center font-black outline-none text-lg text-slate-800 border-none focus:ring-0">
                                    <button @click="quantity++" class="w-12 h-12 flex items-center justify-center font-black text-slate-400 hover:text-custom-primary text-xl">+</button>
                                </div>
                            </div>
                            
                            <div class="flex-[2]">
                                <button @click="addToCart()"
                                    class="w-full h-[64px] bg-slate-900 hover:bg-custom-primary text-white font-black uppercase text-xs tracking-[0.2em] rounded-2xl transition-all shadow-2xl active:scale-95 flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                    Dodaj u korpu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function changeImage(src, btn) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumb-btn').forEach(b => {
                b.classList.remove('border-custom-primary');
                b.classList.add('border-transparent', 'opacity-60');
            });
            btn.classList.add('border-custom-primary');
            btn.classList.remove('border-transparent', 'opacity-60');
        }
    </script>
</x-layouts.app>