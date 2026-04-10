<div>
    {{-- Overlay koji zatvara --}}
    @if($isOpen)
        <div wire:click="$set('isOpen', false)" class="fixed inset-0 bg-black/50 z-[998] backdrop-blur-sm transition-opacity"></div>
    @endif

    {{-- Panel --}}
    <div class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-[999] transition-transform duration-300 transform {{ $isOpen ? 'translate-x-0' : 'translate-x-full' }}">
        <div class="p-6 flex flex-col h-full">
            <div class="flex justify-between items-center border-b pb-4 mb-4">
                <h2 class="text-xl font-bold uppercase italic tracking-tighter">Korpa</h2>
                <button wire:click="$set('isOpen', false)" class="text-2xl font-bold hover:text-custom-primary transition">&times;</button>
            </div>

            {{-- Lista proizvoda --}}
            <div class="flex-grow overflow-y-auto pr-2">
                @forelse($cartItems as $key => $item)
                    <div wire:key="cart-item-{{ $key }}" class="flex gap-4 mb-4 border-b border-slate-50 pb-4 items-center group relative">
                        <img src="{{ asset('storage/' . $item['image']) }}" class="w-16 h-20 object-cover rounded-xl bg-gray-100">
                        
                        <div class="flex-grow">
                            <h4 class="text-[10px] font-bold uppercase pr-8">{{ $item['name'] }}</h4>
                            
                            @if(!empty($item['options']))
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach($item['options'] as $optKey => $optValue)
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                            {{ $optKey }}: <span class="text-slate-900">{{ $optValue }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @endif                          
                            
                            <p class="text-custom-primary font-black mt-1">{{ number_format($item['price'], 0, ',', '.') }} RSD</p>
                            
                            {{-- KONTROLE KOLIČINE --}}
                            <div class="flex items-center gap-3 mt-2">
                                <button wire:click="decrementQuantity('{{ $key }}')" class="w-6 h-6 flex items-center justify-center bg-slate-100 rounded-full text-xs font-bold hover:bg-slate-200">-</button>
                                <span class="text-[11px] font-black">{{ $item['quantity'] }}</span>
                                <button wire:click="incrementQuantity('{{ $key }}')" class="w-6 h-6 flex items-center justify-center bg-slate-100 rounded-full text-xs font-bold hover:bg-slate-200">+</button>
                            </div>
                        </div>

                        {{-- DUGME ZA UKLANJANJE --}}
                        <button 
                            wire:click="removeFromCart('{{ $key }}')" 
                            wire:loading.attr="disabled"
                            class="absolute top-0 right-0 text-slate-300 hover:text-red-600 transition-colors p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-20 text-slate-400">
                        <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p class="uppercase text-[10px] font-bold tracking-widest italic">Vaša korpa je prazna</p>
                    </div>
                @endforelse
            </div>

            @if(count($cartItems) > 0)
                <div class="pt-4 border-t space-y-4">
                    
                    {{-- SEKCIJA ZA KUPON --}}
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <label class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 block mb-2 italic">Imaš promo kod?</label>
                        <div class="flex gap-2">
                            <input 
                                type="text" 
                                wire:model="couponCode" 
                                placeholder="UNESI KOD..." 
                                class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-[11px] font-black uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-custom-primary/20 focus:border-custom-primary transition-all"
                                @if($appliedDiscount) disabled @endif
                            >
                            
                            @if($appliedDiscount)
                                <button wire:click="removeCoupon" class="bg-rose-100 text-rose-600 px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-rose-200 transition-all">
                                    UKLONI
                                </button>
                            @else
                                <button 
                                    wire:click="applyCoupon" 
                                    wire:loading.attr="disabled"
                                    class="bg-slate-900 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-custom-primary transition-colors active:scale-95 disabled:opacity-50"
                                >
                                    <span wire:loading.remove>OK</span>
                                    <span wire:loading>...</span>
                                </button>
                            @endif
                        </div>

                        {{-- Poruke statusa --}}
                        @if (session()->has('coupon_error'))
                            <p class="text-rose-600 text-[9px] font-black mt-2 uppercase italic tracking-tighter">{{ session('coupon_error') }}</p>
                        @endif
                        @if (session()->has('coupon_success'))
                            <p class="text-emerald-600 text-[9px] font-black mt-2 uppercase italic tracking-tighter">{{ session('coupon_success') }}</p>
                        @endif
                    </div>

                    {{-- PRIKAZ CENA --}}
                    <div class="space-y-2 bg-slate-50/50 p-3 rounded-xl">
                        @if($appliedDiscount)
                            <div class="flex justify-between text-[11px] font-black uppercase tracking-widest text-slate-400 italic">
                                <span>Osnovna cena:</span>
                                <span>{{ number_format($subtotal, 0, ',', '.') }} RSD</span>
                            </div>
                            <div class="flex justify-between text-[11px] font-black uppercase tracking-widest text-emerald-600 italic">
                                <span>Popust ({{ $appliedDiscount['percentage'] }}%):</span>
                                <span>-{{ number_format($discountAmount, 0, ',', '.') }} RSD</span>
                            </div>
                        @endif

                        <div class="flex justify-between font-black text-2xl italic tracking-tighter pt-2 border-t border-slate-200">
                            <span>UKUPNO:</span>
                            <span class="text-custom-primary">{{ number_format($totalPrice, 0, ',', '.') }} RSD</span>
                        </div>
                    </div>

                    <a href="/checkout" class="block w-full bg-slate-900 text-white text-center py-4 rounded-xl font-bold uppercase tracking-widest hover:bg-custom-primary transition-all active:scale-95 shadow-lg shadow-slate-200">
                        Nastavi na plaćanje
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>