@php
    // Samostalno izvlačimo aktivni popust direktno iz baze
    // Pretpostavljam da ti se model zove Discount, ako se zove drugačije, promeni ime
    $activeDiscount = \App\Models\Discount::where('is_active', true)->first();
@endphp

@if($activeDiscount)
<section class="max-w-7xl mx-auto px-4 mt-8 mb-10">
    <div class="relative overflow-hidden rounded-[32px] md:rounded-[48px] bg-zinc-900 p-8 md:p-14 text-white shadow-2xl border border-white/5">
        
        <!-- Rose Glow Efekat u pozadini -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-rose-600 rounded-full blur-[100px] opacity-20"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <!-- Bedž -->
                <div class="inline-flex items-center gap-2 bg-rose-600 px-4 py-1.5 rounded-full mb-6 font-black uppercase text-[10px] tracking-widest animate-pulse">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                    </span>
                    Aktivna Akcija
                </div>

                <!-- Naslov Popusta -->
                <h2 class="text-4xl md:text-7xl font-black italic mb-4 uppercase tracking-tighter leading-none">
                    {{ $activeDiscount->name }}
                </h2>

                <!-- Opis -->
                <p class="text-zinc-400 text-lg md:text-xl mb-8 italic font-medium">
                    Ostvari popust od <span class="text-white font-bold underline decoration-rose-600 underline-offset-4">{{ $activeDiscount->percentage }}%</span> na ceo asortiman.<br>
                    <span class="text-sm opacity-75 uppercase tracking-wider not-italic">Obračunava se automatski u korpi</span>
                </p>

                <!-- Dugme -->
                <a href="/shop" class="inline-block bg-white text-zinc-900 px-10 py-4 rounded-full font-black uppercase italic tracking-widest text-xs hover:bg-rose-600 hover:text-white transition-all duration-300 transform hover:scale-105">
                    Iskoristi odmah
                </a>
            </div>

            <!-- Veliki procenat sa strane (Vizuelni detalj) -->
            <div class="text-8xl md:text-[12rem] font-black text-rose-600 opacity-20 italic leading-none select-none pointer-events-none transform md:rotate-12">
                -{{ $activeDiscount->percentage }}%
            </div>
        </div>
    </div>
</section>
@endif