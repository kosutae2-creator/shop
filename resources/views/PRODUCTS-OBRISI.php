<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} | ProShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .thumb-active { border-color: #4f46e5 !important; opacity: 1 !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 h-16 md:h-20 flex justify-between items-center">
            <a href="{{ route('shop.index') }}" class="text-xl md:text-2xl font-black tracking-tighter text-gray-900">
                PRO<span class="text-indigo-600">SHOP.</span>
            </a>

            <!-- Kategorije -->
            <div class="hidden md:flex gap-8 font-semibold text-sm text-gray-600">
                @foreach($categories as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="hover:text-indigo-600 transition-colors">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Korpa -->
            <a href="{{ route('cart.index') }}" class="relative p-2.5 bg-indigo-50 text-indigo-700 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="absolute -top-1.5 -right-1.5 bg-indigo-600 text-white text-[10px] font-bold h-5 w-5 flex items-center justify-center rounded-full border-2 border-white">
                    {{ session('cart') ? count(session('cart')) : 0 }}
                </span>
            </a>
        </div>
    </nav>

    <!-- Main content -->
    <main class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 lg:grid-cols-2 gap-12">
        
        <!-- Product Images -->
        <div class="space-y-6">
            <div class="relative bg-white rounded-3xl overflow-hidden border border-gray-200 shadow-lg">
                <img src="{{ asset('storage/' . $product->image) }}" id="mainImage" class="w-full h-full object-contain p-6 transition-all duration-300">
                <div class="absolute top-4 left-4 bg-indigo-50 text-indigo-600 text-[10px] font-bold px-3 py-1 rounded-full border border-indigo-100">
                    Originalan proizvod
                </div>
            </div>

            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                <button onclick="changeImage('{{ asset('storage/' . $product->image) }}', this)" 
                        class="thumb-btn w-20 h-20 rounded-2xl border-2 border-indigo-500 p-2 flex-shrink-0 thumb-active transition-all">
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-contain">
                </button>
                @foreach($product->images as $extra)
                    <button onclick="changeImage('{{ asset('storage/' . $extra->image_path) }}', this)" 
                            class="thumb-btn w-20 h-20 rounded-2xl border-2 border-transparent opacity-50 p-2 flex-shrink-0 hover:opacity-100 transition-all">
                        <img src="{{ asset('storage/' . $extra->image_path) }}" class="w-full h-full object-contain">
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Product Info -->
        <div class="flex flex-col space-y-6">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight uppercase italic">
                {{ $product->name }}
            </h1>

            <div class="flex items-center gap-4">
                <span class="text-3xl font-black text-indigo-600">{{ number_format($product->price, 0, ',', '.') }} <span class="text-sm font-medium">RSD</span></span>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold uppercase rounded-lg">Dostupno odmah</span>
            </div>

            <div class="text-gray-700 italic leading-relaxed">
                {{ $product->description ?? 'Savršenstvo u svakom detalju. Ovaj model donosi novu dimenziju kvaliteta u vašu svakodnevicu.' }}
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-white rounded-2xl border border-gray-200 text-center">
                    <div class="text-xs font-bold text-gray-400 uppercase mb-1">Dostava</div>
                    <div class="text-sm font-semibold">Danas za sutra</div>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-gray-200 text-center">
                    <div class="text-xs font-bold text-gray-400 uppercase mb-1">Garancija</div>
                    <div class="text-sm font-semibold">24 Meseca</div>
                </div>
            </div>

            <form action="{{ route('cart.store') }}" method="POST" class="bg-indigo-600 p-6 rounded-3xl shadow-xl space-y-4 text-white">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">

                <div class="flex items-center gap-4">
                    <input type="number" name="quantity" value="1" min="1" class="w-16 text-center font-bold rounded-xl text-black">
                    <button type="submit" class="flex-1 bg-white text-indigo-600 font-bold uppercase rounded-xl py-3 hover:bg-gray-100 transition-colors">
                        Dodaj u korpu
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Related products -->
    <section class="max-w-6xl mx-auto px-4 py-24">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-3xl font-extrabold uppercase tracking-tight text-gray-900 italic">Slični proizvodi</h2>
            <a href="{{ route('shop.index') }}" class="text-sm font-bold uppercase text-gray-400 hover:text-indigo-600 transition-colors">Vidi sve →</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($relatedProducts as $related)
                <a href="{{ route('product.show', $related->slug) }}" class="group">
                    <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow hover:shadow-lg transition-transform hover:-translate-y-2">
                        <div class="aspect-square rounded-xl overflow-hidden bg-gray-50 mb-4">
                            <img src="{{ asset('storage/' . $related->image) }}" class="w-full h-full object-contain p-2 transition-transform group-hover:scale-105">
                        </div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase italic truncate">{{ $related->name }}</h3>
                        <div class="text-indigo-600 font-black text-lg">{{ number_format($related->price,0,',','.') }} RSD</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <script>
        function changeImage(src, btn) {
            const mainImg = document.getElementById('mainImage');
            mainImg.style.opacity = '0.5';
            setTimeout(() => { mainImg.src = src; mainImg.style.opacity = '1'; }, 150);

            document.querySelectorAll('.thumb-btn').forEach(b => {
                b.classList.remove('thumb-active');
                b.classList.add('opacity-50', 'border-transparent');
            });
            btn.classList.add('thumb-active');
            btn.classList.remove('opacity-50', 'border-transparent');
        }
    </script>

</body>
</html>