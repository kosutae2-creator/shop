<x-layouts.app>
    {{-- SEO Dinamički podaci --}}
    @section('title', 'Završi kupovinu | ' . ($siteSettings->site_name ?? 'ProShop'))

    <main class="max-w-7xl mx-auto px-4 pt-32 pb-10 md:pt-40 md:pb-20">
        {{-- NASLOV STRANICE --}}
        <div class="mb-12">
            <span class="bg-custom-primary/10 text-custom-primary px-3 py-1 rounded-full text-[10px] font-extrabold mb-4 inline-block uppercase italic tracking-widest">Sigurna kupovina</span>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">
                Završi <span class="text-custom-primary">poručivanje.</span>
            </h1>
        </div>

        {{-- ERROR PORUKE --}}
        @if ($errors->any())
            <div class="bg-rose-50 text-rose-600 p-6 rounded-3xl mb-8 text-sm font-bold border border-rose-100 italic">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('order.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12">
            @csrf
            
            {{-- LEVA KOLONA: FORMA ZA PODATKE --}}
            <div class="lg:col-span-7 space-y-8">
                <div class="bg-white p-8 md:p-10 rounded-[40px] border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center font-black italic text-sm">1</span>
                        <h2 class="text-xl font-black uppercase italic tracking-tight text-slate-800">Podaci za dostavu</h2>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 ml-1 italic">Ime i prezime</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-slate-50 border border-slate-100 p-5 rounded-2xl outline-none transition-all font-bold text-slate-700 placeholder-slate-300 focus:border-custom-primary" placeholder="npr. Marko Marković" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 ml-1 italic">Email adresa</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-50 border border-slate-100 p-5 rounded-2xl outline-none transition-all font-bold text-slate-700 placeholder-slate-300 focus:border-custom-primary" placeholder="marko@email.com" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 ml-1 italic">Broj telefona</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full bg-slate-50 border border-slate-100 p-5 rounded-2xl outline-none transition-all font-bold text-slate-700 placeholder-slate-300 focus:border-custom-primary" placeholder="06x xxx xxxx" required>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 ml-1 italic">Grad</label>
                                <input type="text" name="city" value="{{ old('city') }}" class="w-full bg-slate-50 border border-slate-100 p-5 rounded-2xl outline-none transition-all font-bold text-slate-700 placeholder-slate-300 focus:border-custom-primary" placeholder="Beograd" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 ml-1 italic">Adresa stanovanja</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="w-full bg-slate-50 border border-slate-100 p-5 rounded-2xl outline-none transition-all font-bold text-slate-700 placeholder-slate-300 focus:border-custom-primary" placeholder="Ulica i broj" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="w-8 h-8 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center font-black italic text-sm">2</span>
                        <div>
                            <h2 class="text-lg font-black uppercase italic tracking-tight text-slate-800 leading-none mb-1">Način plaćanja</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase italic tracking-widest">Plaćanje prilikom preuzimanja (Pouzećem)</p>
                        </div>
                    </div>
                    <svg class="w-8 h-8 text-custom-primary opacity-20 hidden md:block" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
                </div>
            </div>

            {{-- DESNA KOLONA: REZIME KORPE --}}
            <div class="lg:col-span-5">
                <div class="bg-slate-900 text-white p-8 md:p-10 rounded-[44px] shadow-2xl sticky top-24 border border-slate-800">
                    <h2 class="text-xl font-black uppercase italic tracking-tight mb-8">Rezime porudžbine</h2>
                    
                    <div class="space-y-6 mb-10 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($cart as $key => $item)
                            <div class="flex gap-4 items-center border-b border-white/5 pb-6 last:border-0">
                                <div class="w-16 h-16 bg-white/10 rounded-2xl overflow-hidden flex-shrink-0 border border-white/10">
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[11px] font-black uppercase tracking-tight truncate italic">{{ $item['name'] }}</h4>
                                    
                                    @if(!empty($item['options']))
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @foreach($item['options'] as $optName => $optValue)
                                                <span class="text-[9px] font-bold text-white/40 uppercase tracking-tighter bg-white/5 px-2 py-0.5 rounded italic">
                                                    {{ $optName }}: <span class="text-custom-primary">{{ $optValue }}</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-[10px] font-bold text-white/40 uppercase italic">{{ $item['quantity'] }}x</span>
                                        <span class="text-sm font-black text-white italic tracking-tighter">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} RSD</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-4 pt-6 border-t border-white/10">
                        @php
                            // 1. Osnovna cena
                            $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
                            
                            // 2. Provera Kupona (iz sesije + provera u bazi da li je i dalje aktivan)
                            $couponDiscountAmount = 0;
                            $appliedCoupon = null;

                            if(session()->has('applied_coupon')) {
                                $couponSession = session()->get('applied_coupon');
                                // Direktna provera u bazi da li je admin u međuvremenu deaktivirao kupon
                                $discountModel = \App\Models\Discount::where('code', $couponSession['code'])
                                                ->where('is_active', true)
                                                ->first();

                                if($discountModel && $discountModel->isValid($subtotal)) {
                                    $appliedCoupon = $couponSession;
                                    $couponDiscountAmount = ($subtotal * $appliedCoupon['percentage']) / 100;
                                } else {
                                    // Ako više nije aktivan u adminu, brišemo ga iz sesije tiho
                                    session()->forget('applied_coupon');
                                }
                            }

                            // 3. Globalni popust (ako postoji)
                            $globalDiscountAmount = 0;
                            if(isset($globalDiscount) && $globalDiscount) {
                                $globalDiscountAmount = ($subtotal * $globalDiscount->percentage) / 100;
                            }

                            // 4. Finalni total
                            $finalTotal = $subtotal - $couponDiscountAmount - $globalDiscountAmount;
                        @endphp

                        <div class="flex justify-between items-center text-white/50 text-[10px] font-black uppercase tracking-widest italic">
                            <span>Međuzbir:</span>
                            <span class="text-white">{{ number_format($subtotal, 0, ',', '.') }} RSD</span>
                        </div>

                        {{-- PRIKAZ KUPONA --}}
                        @if($appliedCoupon)
                        <div class="flex justify-between items-center text-emerald-400 text-[10px] font-black uppercase tracking-widest italic animate-pulse">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12.76 3.76a2 2 0 0 1 2.48 0l.24.21a2 2 0 0 0 2.44.25l.29-.16a2 2 0 0 1 2.44.66l.16.29a2 2 0 0 0 .25 2.44l.21.24a2 2 0 0 1 0 2.48l-.21.24a2 2 0 0 0-.25 2.44l.16.29a2 2 0 0 1-.66 2.44l-.29.16a2 2 0 0 0-2.44.25l-.24.21a2 2 0 0 1-2.48 0l-.24-.21a2 2 0 0 0-2.44-.25l-.29.16a2 2 0 0 1-2.44-.66l-.16-.29a2 2 0 0 0-.25-2.44l-.21-.24a2 2 0 0 1 0-2.48l.21-.24a2 2 0 0 0 .25-2.44l-.16-.29a2 2 0 0 1 .66-2.44l.29-.16a2 2 0 0 0 2.44-.25l.24-.21zM11 15l5-5-1.41-1.41L11 12.17l-2.09-2.08L7.5 11.5 11 15z"/></svg>
                                Kupon: {{ $appliedCoupon['code'] }} (-{{ $appliedCoupon['percentage'] }}%)
                            </span>
                            <span>-{{ number_format($couponDiscountAmount, 0, ',', '.') }} RSD</span>
                        </div>
                        @endif

                        {{-- GLOBALNI POPUST --}}
                        @if($globalDiscountAmount > 0)
                        <div class="flex justify-between items-center text-rose-400 text-[10px] font-black uppercase tracking-widest italic">
                            <span>Akcijski Popust ({{ $globalDiscount->percentage }}%):</span>
                            <span>-{{ number_format($globalDiscountAmount, 0, ',', '.') }} RSD</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center text-white/50 text-[10px] font-black uppercase tracking-widest italic">
                            <span>Dostava:</span>
                            <span class="text-emerald-400 italic font-black uppercase">Besplatna</span>
                        </div>

                        <div class="pt-8 flex flex-col items-end">
                            <span class="text-[10px] font-black uppercase italic tracking-widest text-white/30 mb-1">Ukupno za plaćanje:</span>
                            <span class="text-5xl font-black tracking-tighter text-custom-primary italic">
                                {{ number_format($finalTotal, 0, ',', '.') }} <span class="text-sm font-bold uppercase not-italic text-white">RSD</span>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-8 bg-custom-primary hover:bg-white hover:text-custom-primary text-white py-6 rounded-3xl font-black text-sm uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 italic">
                        Potvrdi kupovinu
                    </button>
                </div>
            </div>
        </form>
    </main>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    </style>
</x-layouts.app>