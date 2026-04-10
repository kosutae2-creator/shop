<div x-show="quickViewOpen" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4" 
     x-cloak 
     style="display: none;">
    
    {{-- Overlay --}}
    <div @click="quickViewOpen = false" x-show="quickViewOpen" x-transition:opacity class="absolute inset-0 bg-slate-900/90 backdrop-blur-md"></div>
    
    {{-- Sadržaj Modala --}}
    <div class="bg-white w-full max-w-4xl rounded-[2rem] overflow-hidden relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto"
         x-show="quickViewOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        
        <button @click="quickViewOpen = false" class="absolute top-6 right-6 z-50 p-2 bg-white/80 rounded-full text-slate-400 hover:text-slate-900 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>

        <template x-if="activeProduct">
            <div class="flex flex-col md:flex-row w-full">
                <div class="w-full md:w-1/2 bg-slate-50 aspect-square">
                    <img :src="'/storage/' + activeProduct.image" class="w-full h-full object-cover">
                </div>
                <div class="w-full md:w-1/2 p-6 md:p-10 flex flex-col">
                    <h2 class="text-xl md:text-3xl font-black uppercase italic tracking-tighter mb-4" x-text="activeProduct.name"></h2>
                    <div class="mb-6">
                        <span class="text-2xl md:text-3xl font-black text-custom-primary italic" 
                              x-text="new Intl.NumberFormat('sr-RS').format(activeProduct.price) + ' RSD'">
                        </span>
                    </div>
                    <p class="text-slate-500 mb-8 text-xs font-bold uppercase leading-relaxed" x-text="activeProduct.description"></p>
                    <div class="mt-auto">
                        <button @click="$dispatch('addToCart', { productId: activeProduct.id }); quickViewOpen = false" 
                                class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-custom-primary transition-all">
                            Dodaj u korpu
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>