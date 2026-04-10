<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings->site_name ?? 'ProShop' }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        :root { 
            --primary-color: {{ $siteSettings->primary_color ?? '#4f46e5' }}; 
        }
        .bg-custom-primary { background-color: var(--primary-color); }
        .text-custom-primary { color: var(--primary-color); }
        .border-custom-primary { border-color: var(--primary-color); }
    </style>
    
    @livewireStyles
</head>
<body class="bg-[#fcfcfd] text-slate-900">

    <!-- 1. TOP BAR -->
    @if($siteSettings && $siteSettings->top_bar_active)
        @include('layouts.partials.top-bar')
    @endif

    <!-- 2. NAVIGACIJA (Uvek na vrhu) -->
    @include('layouts.partials.navbar')

    <!-- 3. GLAVNI SADRŽAJ -->
    <main class="relative">
       
       
        <!-- Ovde upada welcome.blade ili bilo koja druga stranica -->
        {{ $slot }}

    </main>

<!-- Uključujemo baner samo ako je korisnik na početnoj strani (/) -->
    @if(request()->is('/'))
        @include('layouts.partials.banner')
    @endif

    <!-- 4. FOOTER -->
    @include('layouts.partials.footer')

    <!-- KORPA (Side Panel) -->
    @livewire('cart-panel')

    @livewireScripts

    <script>
        function toggleMenu() {
            document.getElementById('mobile-menu').classList.toggle('active');
            document.getElementById('mobile-overlay').classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }
    </script>

</body>
</html>