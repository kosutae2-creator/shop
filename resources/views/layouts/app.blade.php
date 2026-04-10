@php
    if(!isset($categories)) {
        $categories = \App\Models\Category::whereNull('parent_id')->with('children')->get();
    }
    if(!isset($siteSettings)) {
        $siteSettings = \App\Models\SiteSetting::first();
    }
@endphp
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteSettings->seo_title ?? 'Vision Store')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        :root { --primary-color: {{ $siteSettings->primary_color ?? '#4f46e5' }}; }
        .text-custom-primary { color: var(--primary-color); }
        .bg-custom-primary { background-color: var(--primary-color); }
        
        #mobile-menu { transform: translateX(-100%); transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        #mobile-menu.active { transform: translateX(0); }
        .submenu-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
        .submenu-content.active { max-height: 500px; }
    </style>
    
    @livewireStyles
</head>
<body class="bg-[#fcfcfd] text-slate-900">

    @if($siteSettings && $siteSettings->top_bar_active)
        <div class="text-white text-[9px] md:text-xs text-center py-2 px-4 font-bold uppercase tracking-widest sticky top-0 z-[70]"
             style="background-color: {{ $siteSettings->top_bar_bg_color }};">
            {{ $siteSettings->top_bar_text }}
        </div>
    @endif

    <nav x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 5)"
         :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm' : 'bg-white'"
         class="sticky top-[32px] z-50 transition-all duration-300 border-b border-slate-50">
        <div class="max-w-7xl mx-auto px-4 h-16 md:h-20 flex justify-between items-center">
            
            <div class="flex-1 md:hidden">
                <button onclick="toggleMenu()" class="p-2 -ml-2 hover:bg-slate-50 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 8h16M4 16h16"/></svg>
                </button>
            </div>

            <div class="hidden md:flex flex-1 items-center gap-8">
                @foreach($categories->take(4) as $category)
                    <div class="relative group py-4">
                        <a href="#" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-900 hover:text-custom-primary transition-colors flex items-center gap-1">
                            {{ $category->name }}
                            <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <div class="absolute top-full left-0 w-48 bg-white shadow-xl rounded-2xl p-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 border border-slate-50">
                            @foreach($category->children as $child)
                                <a href="/kategorija/{{ $child->slug }}" class="block py-2 text-[9px] font-bold text-slate-500 hover:text-custom-primary uppercase tracking-widest">{{ $child->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="/" class="flex flex-col items-center shrink-0">
                <span class="text-xl md:text-2xl font-black tracking-tighter text-slate-900 uppercase italic leading-none">
                    {{ $siteSettings->site_name }}
                </span>
                <span class="text-[8px] font-bold tracking-[0.3em] text-custom-primary uppercase mt-1">Store</span>
            </a>

            <div class="flex-1 flex items-center justify-end gap-2 md:gap-4">
                @livewire('product-search')
                <button onclick="Livewire.emit('openCart')" class="p-2 hover:bg-slate-50 rounded-xl transition-colors relative group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- Overlay & Mobile Menu --}}
    <div id="mobile-overlay" onclick="toggleMenu()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[80] hidden transition-opacity duration-300"></div>
    <div id="mobile-menu" class="fixed top-0 left-0 bottom-0 w-[300px] bg-white z-[90] p-8 overflow-y-auto">
        <div class="flex justify-between items-center mb-12">
            <span class="text-xl font-black italic tracking-tighter uppercase">{{ $siteSettings->site_name }}</span>
            <button onclick="toggleMenu()" class="p-2 hover:bg-slate-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="space-y-6">
            @foreach($categories as $category)
                <div class="space-y-4">
                    <button onclick="toggleSubmenu('cat-{{ $category->id }}')" class="w-full flex justify-between items-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-900">
                        <span>{{ $category->name }}</span>
                        <svg id="icon-cat-{{ $category->id }}" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <div id="cat-{{ $category->id }}" class="submenu-content pl-4 space-y-3">
                        @foreach($category->children as $child)
                            <a href="/kategorija/{{ $child->slug }}" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $child->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>
    </div>

    {{-- GLAVNI SADRŽAJ --}}
    <main>
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-100 pt-16 pb-8 mt-20">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <div class="md:col-span-2">
                <span class="text-2xl font-black tracking-tighter text-slate-900 uppercase italic">{{ $siteSettings->site_name }}</span>
                <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wide mt-4 italic">{{ $siteSettings->footer_about }}</p>
            </div>
            <div>
                <h4 class="font-black text-[10px] uppercase tracking-[0.3em] mb-8 text-slate-900">Podrška</h4>
                <div class="space-y-4 font-black uppercase text-[10px] tracking-widest text-slate-500">
                    <p class="text-custom-primary italic">{{ $siteSettings->contact_email }}</p>
                    <p>{{ $siteSettings->contact_phone }}</p>
                </div>
            </div>
        </div>
        <div class="text-center border-t border-slate-50 pt-8 mx-6">
            <p class="text-slate-300 text-[9px] font-black uppercase tracking-[0.2em] italic">
                © {{ date('Y') }} {{ $siteSettings->site_name }}. SVA PRAVA ZADRŽANA.
            </p>
        </div>
    </footer>

    @livewire('cart-panel')
    @livewireScripts

    <script>
        function toggleMenu() {
            document.getElementById('mobile-menu').classList.toggle('active');
            document.getElementById('mobile-overlay').classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }
        function toggleSubmenu(id) {
            document.getElementById(id).classList.toggle('active');
            document.getElementById('icon-' + id).classList.toggle('rotate-180');
        }
    </script>
</body>
</html>