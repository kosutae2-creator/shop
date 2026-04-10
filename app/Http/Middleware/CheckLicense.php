<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CheckLicense
{
    public function handle(Request $request, Closure $next)
    {
       
       
        // 1. Dobijamo trenutni host
        $currentHost = $request->getHost();

        // LOGIKA ZA LOKALNI RAD
        $domainToSend = $currentHost;
        if (in_array($currentHost, ['localhost', '127.0.0.1']) || 
            str_ends_with($currentHost, '.test') || 
            str_ends_with($currentHost, '.local')) {
            $domainToSend = '127.0.0.1';
        }

        $cacheKey = "license_status_" . $domainToSend;

        // PRIVREMENO: Smanjili smo keš na 2 sekunde radi lakšeg testiranja
        $data = Cache::remember($cacheKey, now()->addSeconds(2), function () use ($domainToSend) {
            // Koristimo port 8000 jer je tamo obično Master Admin
            $masterUrl = "http://127.0.0.1:8000/api/check-license";
            $licenseKey = env('LICENSE_KEY');

            try {
                $response = Http::timeout(3)->get($masterUrl, [
                    'domain' => $domainToSend,
                    'license_key' => $licenseKey,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                return null;
            }
            return null;
        });

        // --- DEBUG TEST (OBRIŠI OVO KAD PRORADI) ---
        // Ako želiš da vidiš šta Master šalje, otkomentariši liniju ispod:
        // dd($data); 
        // ------------------------------------------

        // 1. Ako nema odgovora od Mastera (npr. ugašen server), pusti sajt da radi
        if (!$data) {
            return $next($request);
        }

        // 2. Ako status nije 'active', blokiraj sajt
        if (($data['status'] ?? '') !== 'active') {
            Cache::forget($cacheKey);
            return $this->blockSite($data['message'] ?? 'Licenca je suspendovana.');
        }

        // 3. Ubacujemo podešavanja u config da bi ih ProductResource video
        // VEOMA BITNO: config ključ mora biti 'app.remote_settings'
        config(['app.remote_settings' => $data['settings'] ?? []]);
        
        // Delimo i sa Blade fajlovima
        view()->share('remote', $data['settings'] ?? []);

        return $next($request);
    }

    private function blockSite($message)
    {
        return response("
            <div style='text-align:center;padding:100px;font-family:sans-serif;background:#f8f9fa;height:100vh;'>
                <div style='background:white;padding:50px;display:inline-block;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.1);'>
                    <h1 style='color:#e3342f;'>Sajt je Privremeno Onemogućen</h1>
                    <p style='color:#6c757d;font-size:18px;'>$message</p>
                    <hr style='border:0;border-top:1px solid #eee;margin:20px 0;'>
                    <p style='font-size:13px;color:#999;'>Status: Provera u toku... Osvežite stranicu.</p>
                    <button onclick='window.location.reload()' style='margin-top:20px;padding:10px 20px;background:#3490dc;color:white;border:none;border-radius:5px;cursor:pointer;'>Osveži Stranicu</button>
                </div>
            </div>
        ", 403)->header('Content-Type', 'text/html');
    }
}