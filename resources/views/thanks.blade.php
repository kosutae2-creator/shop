<x-layouts.app>
    @php
        // Pokušavamo da nađemo ID porudžbine iz sesije ili direktno iz URL-a
        $orderId = session('order_id') ?? request()->route('id');
        
        // Pronalazimo porudžbinu u bazi podataka
        $order = \App\Models\Order::find($orderId);
        
        // Izvlačimo samo prvo ime i pretvaramo u velika slova za jak vizuelni efekat
        $firstName = '';
        if ($order && $order->customer_name) {
            $firstName = strtoupper(explode(' ', trim($order->customer_name))[0]);
        }
    @endphp

    <div class="pt-40 pb-24 bg-white dark:bg-zinc-950 min-h-screen">
        <div class="container mx-auto px-4 text-center">
            
            <!-- Minimalistički Check Mark -->
            <div class="mb-10 flex justify-center">
                <div class="w-20 h-20 border-2 border-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Glavni Naslov sa Imenom -->
            <h1 class="text-5xl md:text-8xl font-black text-zinc-900 dark:text-white uppercase italic tracking-tighter mb-6">
                @if($firstName)
                    {{ $firstName }}, <span class="text-rose-600">HVALA!</span>
                @else
                    HVALA NA <span class="text-rose-600">POVERENJU!</span>
                @endif
            </h1>
            
            <p class="text-zinc-500 dark:text-zinc-400 text-lg md:text-xl max-w-2xl mx-auto mb-12 font-medium">
                Tvoja porudžbina je primljena. Potvrda sa detaljima stiže na tvoj email u roku od par minuta.
            </p>

            <!-- Order ID Badge -->
            @if($orderId)
                <div class="inline-block mb-16 px-6 py-2 bg-zinc-100 dark:bg-zinc-900 rounded-sm">
                    <span class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Broj porudžbine:</span>
                    <span class="ml-2 font-bold text-zinc-900 dark:text-white">#{{ $orderId }}</span>
                </div>
            @endif

            <!-- Dugmići (Popravljen Link) -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                {{-- Ako ti je prodavnica na drugoj ruti, promeni "/shop" u tačnu putanju npr "/proizvodi" --}}
                <a href="/shop" 
                   class="px-12 py-5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-black uppercase italic tracking-widest text-sm hover:bg-rose-600 dark:hover:bg-rose-600 dark:hover:text-white transition-colors duration-300 w-full sm:w-auto text-center">
                    Nastavi kupovinu
                </a>
                
                <a href="/" 
                   class="px-12 py-5 border-2 border-zinc-900 dark:border-zinc-800 text-zinc-900 dark:text-zinc-300 font-black uppercase italic tracking-widest text-sm hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-all duration-300 w-full sm:w-auto text-center">
                    Početna strana
                </a>
            </div>

            <!-- Support info -->
            <div class="mt-32">
                <p class="text-[10px] text-zinc-400 uppercase tracking-[0.4em] font-bold">
                    Problem sa porudžbinom? <a href="mailto:podrska@tvojsajt.rs" class="text-rose-600 underline">Piši nam</a>
                </p>
            </div>

        </div>
    </div>
</x-layouts.app>