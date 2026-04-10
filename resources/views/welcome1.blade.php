<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings->site_name ?? 'ProShop' }} | Future Retail</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&family=Outfit:wght@200;400;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0a0a0a; color: #fff; overflow-x: hidden; }
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
        
        :root { --primary: {{ $siteSettings->primary_color ?? '#6366f1' }}; }
        .text-primary { color: var(--primary); }
        .bg-primary { background-color: var(--primary); }

        /* Moderni Gradienti */
        .gradient-mesh {
            background: radial-gradient(circle at 50% -20%, var(--primary), transparent),
                        radial-gradient(circle at 0% 100%, #000, transparent);
        }

        /* Glassmorphism */
        .nav-glass {
            background: rgba(10, 10, 10, 0.7);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        /* Bento Grid Style */
        .bento-card {
            background: #121212;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .bento-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }

        /* Animacije */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .float-anim { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body>

@if($siteSettings && $siteSettings->top_bar_active)
<div class="relative z-[100] bg-primary py-1.5 overflow-hidden">
    <div class="flex whitespace-nowrap animate-marquee items-center">
        @for($i = 0; $i < 10; $i++)
            <span class="text-[10px] font-black uppercase tracking-[0.3em] mx-8 text-black">
                {{ $siteSettings->top_bar_text }} • 
            </span>
        @endfor
    </div>
</div>
<style>
    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
    .animate-marquee { display: flex; animation: marquee 30s linear infinite; }
</style>
@endif

<nav class="nav-glass sticky top-0 z-[90] h-20 flex items-center">
    <div class="max-w-[1400px] mx-auto w-full px-6 flex justify-between items-center">
        <div class="flex items-center gap-12">
            <a href="/" class="text-3xl font-black tracking-tighter font-heading italic">
                {{ $siteSettings->site_name ?? 'NEXT' }}<span class="text-primary">.</span>
            </a>
            <div class="hidden lg:flex gap-10 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                <a href="/" class="hover:text-white transition">Kolekcije</a>
                <a href="/#products" class="hover:text-white transition">Arhiva</a>
                <a href="/kontakt" class="hover:text-white transition">Studio</a>
            </div>
        </div>
        
        <div class="flex items-center gap-6">
            <a href="{{ route('cart.index') }}" class="group relative bg-white text-black px-6 py-2.5 rounded-full flex items-center gap-3 transition hover:bg-primary hover:text-white">
                <span class="text-[10px] font-black uppercase tracking-tighter">Korpa</span>
                <span class="bg-black text-white group-hover:bg-white group-hover:text-black w-5 h-5 flex items-center justify-center rounded-full text-[10px]">
                    {{ session('cart') ? count(session('cart')) : 0 }}
                </span>
            </a>
            <button onclick="toggleMenu()" class="lg:hidden text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 8h16M4 16h16" stroke-width="1.5"/></svg>
            </button>
        </div>
    </div>
</nav>

@if(!isset($category) && ($siteSettings->banner_active ?? true))
<section class="relative min-h-[90vh] flex items-center pt-20 overflow-hidden gradient-mesh">
    <div class="max-w-[1400px] mx-auto px-6 w-full grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-7 relative z-10 text-center lg:text-left">
            <h1 class="text-6xl md:text-[120px] font-black font-heading leading-[0.9] uppercase tracking-tighter mb-8">
                {!! str_replace(' ', '<br>', e($siteSettings->banner_title ?? 'NOVA ERA STILA')) !!}
            </h1>
            <div class="flex flex-col md:flex-row items-center gap-8">
                <a href="#products" class="bg-primary text-white px-12 py-6 rounded-full font-black uppercase tracking-widest text-xs hover:scale-105 transition-transform shadow-[0_0_40px_rgba(99,102,241,0.4)]">
                    Otkrij Kolekciju
                </a>
                <p class="text-slate-400 max-w-[300px] text-sm italic font-light">
                    {{ $siteSettings->banner_description ?? 'Estetika budućnosti dostupna danas.' }}
                </p>
            </div>
        </div>
        <div class="lg:col-span-5 relative">
            <div class="absolute -inset-20 bg-primary/20 blur-[120px] rounded-full animate-pulse"></div>
            <img src="{{ ($siteSettings && $siteSettings->banner_image) ? asset('storage/' . $siteSettings->banner_image) : 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800' }}" 
                 class="relative z-10 w-full h-[600px] object-cover rounded-[60px] grayscale hover:grayscale-0 transition duration-700 float-anim">
        </div>
    </div>
</section>
@endif

<main id="products" class="max-w-[1400px] mx-auto px-6 py-32">
    <div class="flex justify-between items-end mb-20 border-b border-white/10 pb-10">
        <div>
            <h2 class="text-4xl font-black font-heading uppercase tracking-tighter">Izabrani artikli</h2>
            <p class="text-primary font-bold text-xs uppercase tracking-[0.5em] mt-2">Selected Goods 2026</p>
        </div>
        <div class="hidden md:block text-right text-xs text-slate-500 uppercase font-bold tracking-widest">
            Skroluj za istraživanje <br> ↓
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bento-card group relative rounded-[40px] overflow-hidden p-6 flex flex-col min-h-[500px]">
                @if($product->old_price && $product->old_price > $product->price)
                    <div class="absolute top-8 left-8 z-20 bg-white text-black text-[10px] font-black px-4 py-1.5 rounded-full uppercase italic">
                        Sale Item
                    </div>
                @endif

                <div class="relative flex-grow mb-8 overflow-hidden rounded-[30px]">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=600' }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-80 group-hover:opacity-100">
                </div>

                <div class="flex justify-between items-end">
                    <div class="max-w-[70%]">
                        <p class="text-[10px] text-primary font-black uppercase mb-2 tracking-widest italic">In Stock</p>
                        <h3 class="text-2xl font-black uppercase tracking-tight leading-none mb-2 line-clamp-1">{{ $product->name }}</h3>
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-light tracking-tighter">{{ number_format($product->price, 0, ',', '.') }} RSD</span>
                            @if($product->old_price > $product->price)
                                <span class="text-slate-600 line-through text-xs">{{ number_format($product->old_price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('product.show', $product->slug) }}" class="bg-white/5 p-5 rounded-2xl hover:bg-primary transition-colors text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</main>

<footer class="bg-black pt-32 pb-10 border-t border-white/5">
    <div class="max-w-[1400px] mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-20 mb-32">
            <div class="col-span-2">
                <h4 class="text-5xl font-black font-heading mb-8 italic uppercase leading-none">Prijavi se za <br> <span class="text-primary italic">Backstage</span> pristup.</h4>
                <div class="flex gap-4 max-w-md bg-white/5 p-2 rounded-full border border-white/10">
                    <input type="text" placeholder="Email adresa" class="bg-transparent border-none flex-grow pl-6 text-sm focus:ring-0">
                    <button class="bg-white text-black px-8 py-3 rounded-full font-black uppercase text-[10px]">Pridruži se</button>
                </div>
            </div>
            <div>
                <h5 class="text-xs font-black uppercase tracking-[0.3em] mb-8 text-primary">Informacije</h5>
                <ul class="space-y-4 text-sm text-slate-400 font-medium">
                    <li><a href="/" class="hover:text-white transition">Početna</a></li>
                    <li><a href="/kontakt" class="hover:text-white transition">Pomoć i FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition">Dostava</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-xs font-black uppercase tracking-[0.3em] mb-8 text-primary">Kontakt</h5>
                <p class="text-sm text-slate-400 mb-4">{{ $siteSettings->contact_email }}</p>
                <div class="flex gap-4 italic font-black text-xs uppercase tracking-tighter">
                    <a href="#" class="hover:text-primary">Instagram</a>
                    <a href="#" class="hover:text-primary">TikTok</a>
                </div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center text-[10px] text-slate-600 font-bold uppercase tracking-[0.4em] pt-10 border-t border-white/5">
            <p>© 2026 {{ $siteSettings->site_name }} System . All rights reserved</p>
            <p>Designed by AI Future</p>
        </div>
    </div>
</footer>

<div id="mobile-overlay" onclick="toggleMenu()" class="fixed inset-0 bg-black/90 z-[100] hidden backdrop-blur-xl"></div>
<div id="mobile-menu" class="fixed top-0 left-0 bottom-0 w-full bg-black z-[110] p-10 flex flex-col justify-center items-center gap-12 text-center transform -translate-y-full transition-transform duration-500 ease-in-out">
    <button onclick="toggleMenu()" class="absolute top-10 right-10 text-white"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="1.5"/></svg></button>
    <a href="/" class="text-5xl font-black font-heading uppercase italic">Početna</a>
    <a href="/#products" onclick="toggleMenu()" class="text-5xl font-black font-heading uppercase italic">Arhiva</a>
    <a href="/kontakt" class="text-5xl font-black font-heading uppercase italic">Studio</a>
    <div class="mt-12 text-primary text-xs font-bold uppercase tracking-[0.5em] italic">Next Gen Retail</div>
</div>

<script>
    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-overlay');
        menu.classList.toggle('-translate-y-full');
        menu.classList.toggle('translate-y-0');
        overlay.classList.toggle('hidden');
    }
</script>

</body>
</html>