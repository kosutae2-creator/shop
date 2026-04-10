{{-- Navigacija --}}
<nav 
    x-data="{ 
        scrolled: false, 
        mobileMenuOpen: false,
        hasBanner: {{ ($siteSettings && $siteSettings->top_bar_active) ? 'true' : 'false' }}
    }" 
    @scroll.window="scrolled = (window.pageYOffset > 30)"
    class="fixed inset-x-0 z-[100] transition-all duration-300"
    :class="{
        'bg-white/95 backdrop-blur-lg shadow-xl border-b border-slate-200 py-2 !top-0 mx-0 rounded-none': scrolled,
        'bg-white border-b border-slate-100 py-3 mx-0 md:mx-4 md:rounded-2xl': !scrolled,
        'top-8': !scrolled && hasBanner,
        'top-0': !scrolled && !hasBanner
    }"
>
    <div class="max-w-7xl mx-auto px-4 h-16 md:h-20 flex justify-between items-center">
        
        {{-- Logo i Mobile Trigger --}}
        <div class="flex items-center gap-4">
            <button @click="mobileMenuOpen = true" class="md:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
            <a href="/" class="text-xl md:text-2xl font-black tracking-tighter italic group">
                PRO<span class="text-custom-primary group-hover:text-slate-900 transition-colors duration-300">SHOP.</span>
            </a>
        </div>

        {{-- Desktop Menu --}}
        <div class="hidden md:flex items-center gap-8 font-bold text-xs uppercase tracking-widest h-full">
            <a href="/" class="text-slate-600 hover:text-custom-primary transition relative group">
                Početna
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-custom-primary transition-all group-hover:w-full"></span>
            </a>
            
            {{-- Desktop Kategorije Dropdown --}}
            <div class="relative h-full flex items-center" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="flex items-center gap-1 text-slate-600 group-hover:text-custom-primary transition py-8 uppercase">
                    Kategorije 
                    <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="open" x-cloak x-transition class="absolute bg-white shadow-2xl border border-slate-100 rounded-2xl p-2 min-w-[260px] top-full -mt-2 z-[110]">
                    @foreach($categories as $cat)
                        @if(is_null($cat->parent_id))
                            <div class="relative group-sub" x-data="{ subOpen: false }" @mouseenter="subOpen = true" @mouseleave="subOpen = false">
                                <a href="{{ route('category.show', $cat->slug) }}" 
                                   class="flex justify-between items-center px-4 py-3 hover:bg-slate-50 rounded-xl text-slate-700 hover:text-custom-primary text-[11px] transition-colors">
                                    {{ $cat->name }}
                                    @if($cat->children->count() > 0)
                                        <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @endif
                                </a>
                                @if($cat->children->count() > 0)
                                    <div x-show="subOpen" x-cloak class="absolute left-full top-0 -ml-1 pl-1 min-w-[220px] z-[120]">
                                        <div class="bg-white shadow-2xl border border-slate-100 rounded-2xl p-2">
                                            @foreach($cat->children as $child)
                                                <a href="{{ route('category.show', $child->slug) }}" class="block px-4 py-2 text-[11px] text-slate-600 hover:text-custom-primary hover:bg-slate-50 rounded-lg">
                                                    {{ $child->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <a href="/kontakt" class="text-slate-600 hover:text-custom-primary transition text-xs uppercase tracking-widest">Kontakt</a>
        </div>

        {{-- Search & Cart --}}
        <div class="flex items-center gap-2 md:gap-4">
            @livewire('product-search')
            <button @click="$dispatch('toggle-cart')" class="relative p-3 bg-slate-900 text-white rounded-xl hover:bg-custom-primary transition-all shadow-lg active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                @livewire('cart-count')
            </button>
        </div>
    </div>

    {{-- MOBILNI MENI FULLSCREEN --}}
    <div 
        x-show="mobileMenuOpen" 
        x-cloak
        class="fixed inset-0 z-[200] md:hidden"
    >
        {{-- Overlay --}}
        <div 
            x-show="mobileMenuOpen"
            x-transition:opacity
            @click="mobileMenuOpen = false"
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"
        ></div>

        {{-- Sadržaj drawer-a --}}
        <div 
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative bg-white w-[85%] max-w-[320px] h-screen shadow-2xl p-6 flex flex-col overflow-y-auto"
        >
            <div class="flex justify-between items-center mb-10">
                <span class="font-black text-2xl text-slate-900 italic">PRO<span class="text-custom-primary">SHOP.</span></span>
                <button @click="mobileMenuOpen = false" class="p-2 text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="flex flex-col gap-2 font-bold text-slate-800 uppercase text-sm tracking-tight">
                <a href="/" class="py-4 border-b border-slate-50">Početna</a>
                
                {{-- Mobilni Dropdown za kategorije --}}
                <div x-data="{ shopOpen: false }">
                    <button @click="shopOpen = !shopOpen" class="w-full flex justify-between items-center py-4 border-b border-slate-50 uppercase">
                        Prodavnica 
                        <svg class="w-4 h-4 transition-transform" :class="shopOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="shopOpen" x-transition class="bg-slate-50 rounded-xl mt-2 overflow-hidden">
                        @foreach($categories as $cat)
                            @if(is_null($cat->parent_id))
                                <div x-data="{ subOpen: false }" class="border-b border-white last:border-0">
                                    <div class="flex justify-between items-center px-4 py-3">
                                        <a href="{{ route('category.show', $cat->slug) }}" class="text-[12px] font-black italic uppercase text-slate-900">
                                            {{ $cat->name }}
                                        </a>
                                        @if($cat->children->count() > 0)
                                            <button @click="subOpen = !subOpen" class="p-1">
                                                <svg class="w-4 h-4 text-slate-400" :class="subOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    @if($cat->children->count() > 0)
                                        <div x-show="subOpen" x-transition class="pl-8 pb-3 flex flex-col gap-2">
                                            @foreach($cat->children as $child)
                                                <a href="{{ route('category.show', $child->slug) }}" class="text-[11px] font-bold text-slate-500 uppercase">
                                                    {{ $child->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                
                <a href="/kontakt" class="py-4 border-b border-slate-50">Kontakt</a>
            </div>
        </div>
    </div>
</nav>

<style>
    [x-cloak] { display: none !important; }
</style>