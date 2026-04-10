<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hvala na porudžbini! | ProShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .success-gradient { background: radial-gradient(circle at top right, #f5f7ff, #fcfcfd); }
    </style>
</head>
<body class="success-gradient text-slate-900 min-h-screen flex flex-col">

    <nav class="bg-white/70 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-center">
            <a href="/" class="text-xl font-black tracking-tighter">PRO<span class="text-indigo-600">SHOP.</span></a>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="max-w-lg w-full bg-white rounded-[40px] p-10 md:p-16 shadow-2xl shadow-indigo-100/50 border border-slate-50 text-center relative overflow-hidden">
            
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>

            <div class="w-24 h-24 bg-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-xl shadow-emerald-200 rotate-6 transform transition-transform hover:rotate-0 duration-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-3xl md:text-4xl font-black mb-4 tracking-tight text-slate-900">Porudžbina primljena!</h1>
            <p class="text-slate-500 text-base md:text-lg mb-10 leading-relaxed">
                Hvala Vam na poverenju. Vaša porudžbina je uspešno zabeležena u našem sistemu. Naš tim će je obraditi u najkraćem mogućem roku.
            </p>

            <div class="space-y-4">
                <a href="/" class="block w-full bg-indigo-600 text-white font-bold py-5 rounded-2xl hover:bg-indigo-700 transition-all active:scale-95 shadow-lg shadow-indigo-200">
                    Nastavi sa kupovinom
                </a>
                <div class="pt-4">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Očekivana isporuka: 24-48h</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-8 text-center text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em]">
        &copy; 2026 ProShop Luxury Retail &bull; Sva prava zadržana
    </footer>

</body>
</html>