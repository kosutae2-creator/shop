<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tvoja Korpa | ProShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#fcfcfd] text-slate-900">

<nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
    <div class="max-w-5xl mx-auto px-4 h-20 flex justify-between items-center">
        <a href="/" class="text-2xl font-black tracking-tighter italic">PRO<span class="text-indigo-600">SHOP.</span></a>
        <a href="/" class="text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Nazad na shop
        </a>
    </div>
</nav>

<main class="max-w-5xl mx-auto px-4 py-12">
    <div class="flex items-center gap-4 mb-10">
        <h1 class="text-3xl md:text-4xl font-black tracking-tight uppercase italic">Tvoja korpa</h1>
        <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-sm font-bold">
            {{ count($cart) }} {{ count($cart) == 1 ? 'artikal' : 'artikla' }}
        </span>
    </div>

    @if(count($cart) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 space-y-6">
            @foreach($cart as $key => $details)
            <div class="bg-white p-5 rounded-[32px] border border-slate-100 flex gap-6 items-center hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                
                <div class="w-24 h-24 md:w-32 md:h-32 bg-slate-50 rounded-[24px] overflow-hidden flex-shrink-0 border border-slate-50">
                    <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=200' }}" 
                         class="w-full h-full object-cover">
                </div>

                <div class="flex-grow">
                    <h3 class="font-extrabold text-slate-800 text-base md:text-xl leading-tight mb-2 italic uppercase tracking-tight">
                        {{ $details['name'] }}
                    </h3>
                    
                    @if(isset($details['options']) && !empty($details['options']))
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($details['options'] as $label => $value)
                                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-500 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                                    {{ $label }}: {{ $value }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex items-center justify-between mt-4 bg-slate-50/50 p-3 rounded-2xl border border-dashed border-slate-200">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-0.5 italic">Ukupno za ovaj artikal</span>
                            <div class="text-indigo-600 font-black text-lg md:text-2xl tracking-tighter">
                                {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }} <span class="text-[10px] md:text-xs">RSD</span>
                            </div>
                        </div>

                        {{-- Update forme za Livewire 3 - možeš koristiti Livewire ili ostati na POST ruti --}}
                        <div class="bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                            <span class="text-[10px] font-black uppercase text-slate-400">Količina: {{ $details['quantity'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Dugme za uklanjanje --}}
                <form action="{{ route('cart.remove') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $key }}"> 
                    <button type="submit" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        {{-- Desna kolona: Račun --}}
        <div class="lg:col-span-1">
            <div class="bg-slate-900 rounded-[40px] p-8 text-white sticky top-28 shadow-2xl border border-slate-800">
                <h2 class="text-xl font-black mb-8 flex items-center gap-2 italic uppercase tracking-tighter">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(99,102,241,0.6)]"></span>
                    Račun
                </h2>
                
                <div class="space-y-5 mb-10">
                    <div class="flex justify-between text-slate-400 font-bold text-sm uppercase tracking-widest">
                        <span>Međuzbir</span>
                        <span class="text-white font-black">{{ number_format($subtotal, 0, ',', '.') }} <span class="text-[10px]">RSD</span></span>
                    </div>

                    @if($discountAmount > 0)
                        <div class="flex justify-between items-center bg-rose-500/10 p-4 rounded-3xl border border-rose-500/20">
                            <span class="text-rose-400 text-[10px] font-black uppercase italic">Popust</span>
                            <span class="text-rose-400 font-black tracking-tighter">-{{ number_format($discountAmount, 0, ',', '.') }} <span class="text-[10px]">RSD</span></span>
                        </div>
                    @endif

                    <div class="flex justify-between text-slate-400 font-bold text-sm uppercase tracking-widest">
                        <span>Dostava</span>
                        <span class="text-emerald-400 font-black text-[10px] uppercase bg-emerald-400/10 px-3 py-1.5 rounded-xl border border-emerald-400/20 italic">Besplatna</span>
                    </div>

                    <div class="pt-6 border-t border-slate-800 flex justify-between items-end">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Ukupno</span>
                        <span class="text-4xl font-black text-indigo-400 tracking-tighter leading-none">
                            {{ number_format($total, 0, ',', '.') }} <span class="text-xs text-white">RSD</span>
                        </span>
                    </div>
                </div>

                {{-- OVDE JE FIX ZA GREŠKU: Promenjeno checkout.index u checkout --}}
                <a href="{{ route('checkout') }}" class="block w-full bg-indigo-600 hover:bg-indigo-500 text-white text-center py-5 rounded-[24px] font-black uppercase tracking-[0.1em] text-sm transition-all shadow-xl shadow-indigo-900/40 active:scale-[0.96]">
                    Završi porudžbinu
                </a>
            </div>
        </div>
    </div>
    @else
        {{-- Prazna korpa --}}
        <div class="max-w-md mx-auto text-center py-20 bg-white rounded-[48px] border border-slate-100 shadow-sm mt-10 px-6">
            <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-5xl grayscale opacity-30 shadow-inner italic font-black text-slate-300">!</div>
            <h2 class="text-2xl font-black text-slate-800 mb-3 uppercase tracking-tighter italic">Korpa je prazna</h2>
            <a href="/" class="inline-block bg-slate-900 text-white px-10 py-5 rounded-[24px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-2xl shadow-slate-200 active:scale-95">
                Nazad na početnu
            </a>
        </div>
    @endif
</main>

<footer class="max-w-5xl mx-auto px-4 py-12 text-center">
    <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.3em]">
        © 2026 ProShop Premium Store.
    </p>
</footer>

</body>
</html>