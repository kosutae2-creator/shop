<!-- resources/views/products-obrisi.blade.php -->
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name ?? 'Proizvod' }} | ProShop Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f4f9; }
        .glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); }
        .thumb-active { border-color: #6366f1 !important; opacity: 1 !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="text-gray-900">

<!-- Navbar -->
<nav class="fixed top-0 left-0 w-full z-50 glass border-b border-gray-200 shadow-md">
    <div class="max-w-7xl mx-auto px-6 h-16 flex justify-between items-center">
        <a href="{{ route('shop.index') }}" class="text-2xl font-extrabold tracking-tight text-gray-900">
            PRO<span class="text-indigo-600">SHOP</span>
        </a>
        <div class="hidden md:flex gap-6 font-semibold text-gray-700">
            @foreach($categories as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="hover:text-indigo-600 transition-colors">{{ $cat->name }}</a>
            @endforeach
        </div>
        <a href="{{ route('cart.index') }}" class="relative p-2 bg-indigo-100 text-indigo-700 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="absolute -top-1.5 -right-1.5 bg-indigo-600 text-white text-[10px] font-bold h-5 w-5 flex items-center justify-center rounded-full border-2 border-white">
                {{ session('cart') ? count(session('cart')) : 0 }}
            </span>
        </a>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-6 pt-32 pb-24 grid grid-cols-1 lg:grid-cols-2 gap-16">

    <!-- LEVA STRANA: Galerija -->
    <div class="space-y-6">
        <div class="relative rounded-3xl overflow-hidden shadow-lg border border-gray-200">
            <img id="mainImage" src="{{ asset('storage/' . $product->image) ?? 'https://via.placeholder.com/600' }}" class="w-full h-[450px] object-contain bg-white">
        </div>

        <div class="flex gap-4 overflow-x-auto scrollbar-hide">
            <button onclick="changeImage('{{ asset('storage/' . $product->image) }}', this)" class="thumb-btn w-20 h-20 border-2 border-indigo-600 rounded-xl p-1 thumb-active">
                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-contain">
            </button>
            @foreach($product->images as $img)
                <button onclick="changeImage('{{ asset('storage/' . $img->image_path) }}', this)" class="thumb-btn w-20 h-20 border-2 border-transparent rounded-xl p-1 opacity-50 hover:opacity-100 transition-all">
                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-contain">
                </button>
            @endforeach
        </div>
    </div>

    <!-- DESNA STRANA: Info & Kupovina -->
    <div class="flex flex-col justify-start gap-6">
        <h1 class="text-4xl font-bold tracking-tight uppercase">{{ $product->name }}</h1>
        <div class="text-3xl font-extrabold text-indigo-600">{{ number_format($product->price,0,',','.') }} RSD</div>
        <p class="text-gray-600 italic leading-relaxed">{{ $product->description ?? 'Opis proizvoda nije dostupan.' }}</p>

        <form action="{{ route('cart.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}">
            
            <div class="flex items-center gap-4">
                <div class="flex items-center border rounded-xl overflow-hidden">
                    <button type="button" onclick="this.nextElementSibling.stepDown()" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 transition-all">-</button>
                    <input type="number" name="quantity" value="1" min="1" class="w-16 text-center font-bold outline-none">
                    <button type="button" onclick="this.previousElementSibling.stepUp()" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 transition-all">+</button>
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl transition-all">Dodaj u korpu</button>
            </div>
        </form>

        <div class="flex gap-4 mt-6">
            <div class="bg-white p-4 rounded-xl shadow-md flex-1 text-center">
                <span class="text-gray-400 uppercase text-xs">Dostava</span>
                <div class="font-bold">Danas za sutra</div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md flex-1 text-center">
                <span class="text-gray-400 uppercase text-xs">Garancija</span>
                <div class="font-bold">24 Meseca</div>
            </div>
        </div>
    </div>
</main>

<!-- Slični proizvodi -->
<section class="max-w-7xl mx-auto px-6 py-16">
    <h2 class="text-2xl font-bold text-gray-900 uppercase mb-6">Slični proizvodi</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($relatedProducts as $related)
        <a href="{{ route('product.show', $related->slug) }}" class="group">
            <div class="bg-white p-4 rounded-2xl shadow hover:shadow-lg transition-all">
                <img src="{{ asset('storage/' . $related->image) }}" class="w-full h-48 object-contain mb-2">
                <h3 class="font-semibold text-gray-900 truncate">{{ $related->name }}</h3>
                <p class="text-indigo-600 font-bold">{{ number_format($related->price,0,',','.') }} RSD</p>
            </div>
        </a>
        @endforeach
    </div>
</section>

<script>
    function changeImage(src, btn) {
        const main = document.getElementById('mainImage');
        main.style.opacity = 0.5;
        setTimeout(() => { main.src = src; main.style.opacity = 1; }, 150);

        document.querySelectorAll('.thumb-btn').forEach(b => {
            b.classList.remove('thumb-active');
            b.classList.add('opacity-50','border-transparent');
        });
        btn.classList.add('thumb-active');
        btn.classList.remove('opacity-50','border-transparent');
    }
</script>

</body>
</html>