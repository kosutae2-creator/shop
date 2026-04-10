<x-layouts.app>
    {{-- 
        GLAVNI KONTEJNER: 
        Dodajemo pt-32 (ili pt-40) da navigacija ne prekriva Hero ili Naslov kategorije.
    --}}
    <div x-data="{ quickViewOpen: false, activeProduct: null }" class="relative pt-24 md:pt-32">
        
        {{-- 1. HERO SEKCIJA - Prikazuje se SAMO na početnoj stranici --}}
        @if(!isset($category))
            <section class="relative bg-zinc-900 min-h-[500px] md:min-h-[600px] flex items-center overflow-hidden rounded-[32px] mx-4 mb-8">
                <div class="absolute inset-0 opacity-40">
                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070" 
                         class="w-full h-full object-cover" alt="Hero Background">
                </div>
                <div class="max-w-7xl mx-auto px-6 relative z-10 text-white w-full">
                    <div class="max-w-2xl">
                        <span class="inline-block px-4 py-1 bg-rose-600 text-[10px] font-black tracking-[0.3em] uppercase mb-6">
                            Nova Kolekcija 2026
                        </span>
                        <h1 class="text-6xl md:text-8xl font-black italic tracking-tighter mb-8 leading-[0.9] uppercase">
                            DEFINIŠI <br> <span class="text-rose-600">SVOJ STIL.</span>
                        </h1>
                        <a href="#proizvodi" class="inline-flex items-center gap-4 bg-white text-zinc-900 px-10 py-5 rounded-full font-black uppercase text-xs tracking-widest hover:bg-rose-600 hover:text-white transition-all group active:scale-95">
                            Kupi Odmah
                        </a>
                    </div>
                </div>
            </section>

            {{-- 2. AKCIJSKI BANER (Marquee) - Takođe samo na početnoj --}}
            <div class="bg-rose-600 py-4 overflow-hidden whitespace-nowrap border-y border-white/10 mb-12">
                <div class="flex animate-marquee items-center">
                    @for ($i = 0; $i < 10; $i++)
                        <span class="text-white font-black uppercase tracking-[0.4em] text-[11px] mx-12 flex items-center gap-4 italic">
                            Popust do 50% na odabrane artikle •
                        </span>
                    @endfor
                </div>
            </div>
        @endif

        {{-- 3. NASLOV KATEGORIJE - Prikazuje se SAMO kada je kategorija aktivna --}}
        @if(isset($category))
            <section class="max-w-7xl mx-auto px-6 pt-10 pb-12">
                <nav class="flex mb-6 uppercase tracking-[0.3em] text-[10px] font-black text-zinc-400">
                    <a href="/" class="hover:text-rose-600 transition-colors text-zinc-500">Početna</a>
                    <span class="mx-3">/</span>
                    <span class="text-zinc-900 underline decoration-rose-600 decoration-2 underline-offset-8">Kategorija</span>
                </nav>
                <h1 class="text-6xl md:text-9xl font-black text-zinc-900 uppercase italic tracking-tighter leading-none">
                    {{ $category->name }}<span class="text-rose-600">.</span>
                </h1>
                @if($category->description)
                    <p class="mt-6 text-zinc-500 max-w-2xl font-medium text-lg italic">
                        {{ $category->description }}
                    </p>
                @endif
            </section>
        @endif

        {{-- 4. IZDVAJAMO - Prikazuje se samo na početnoj --}}
        @if(!isset($category) && isset($featuredProducts) && $featuredProducts->count() > 0)
            <section id="izdvajamo" class="py-12 md:py-20 bg-zinc-50 border-b border-zinc-100">
                <div class="max-w-7xl mx-auto px-4 md:px-6">
                    <div class="mb-12">
                        <h2 class="text-4xl md:text-5xl font-black italic tracking-tighter uppercase text-zinc-900 leading-none">
                            Izdvajamo<span class="text-rose-600">/</span>
                        </h2>
                        <p class="text-zinc-400 mt-2 uppercase text-[10px] tracking-[0.2em] font-bold">Naši najtraženiji komadi</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
                        @foreach($featuredProducts as $product)
                            @include('layouts.partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- 5. SVI PROIZVODI (Grid) --}}
        <section id="proizvodi" class="py-12 md:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 md:px-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-12 gap-4 border-b border-zinc-100 pb-8">
                    <div>
                        <h2 class="text-4xl md:text-5xl font-black italic tracking-tighter uppercase text-zinc-900 leading-none">
                            @if(isset($category)) 
                                Proizvodi u ponudi 
                            @else 
                                Svi Proizvodi 
                            @endif
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-10">
                    @forelse($products as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @empty
                        <div class="col-span-full py-24 text-center">
                            <div class="inline-block p-10 rounded-full bg-zinc-50 mb-6">
                                <svg class="w-12 h-12 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <p class="text-zinc-400 font-black uppercase italic tracking-widest">Trenutno nema dostupnih proizvoda.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Paginacija --}}
                <div class="mt-20 flex justify-center">
                    {{ $products->links() }}
                </div>
            </div>
        </section>

        {{-- QUICK VIEW MODAL --}}
        @include('layouts.partials.quick-view-modal')

    </div> {{-- KRAJ GLAVNOG X-DATA --}}
</x-layouts.app>