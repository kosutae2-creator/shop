<?php

class LicenseChecker {
    // URL tvog MasterAdmina
    private $master_url = "http://127.0.0.1:8000/api/check-license";
    private $domain;
    private $license_key;

    public function __construct($domain, $license_key) {
        $this->domain = $domain;
        $this->license_key = $license_key;
    }

    public function verify() {
        // Kreiramo pun URL sa parametrima
        $url = $this->master_url . "?domain=" . urlencode($this->domain) . "&license_key=" . urlencode($this->license_key);

        // Koristimo cURL za poziv API-ja
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Ako MasterAdmin ne odgovori za 5s, pusti dalje (opciono)
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            $data = json_decode($response, true);
            if ($data['status'] === 'active') {
                return $data; // Sve je u redu, vraćamo podatke (settings)
            }
        }

        // Ako dođe do ovde, licenca nije validna
        $this->blockSite($response);
    }

    private function blockSite($response) {
        $data = json_decode($response, true);
        $message = $data['message'] ?? "Licenca za ovaj sajt nije aktivna.";
        
        // Jednostavan "Access Denied" dizajn
        die("
            <div style='text-align:center; padding:50px; font-family:sans-serif; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:10px; margin:50px auto; max-width:600px;'>
                <h1>Pristup Ograničen</h1>
                <p style='font-size:18px;'>$message</p>
                <hr style='border:0; border-top:1px solid #f5c6cb;'>
                <p>Molimo kontaktirajte podršku na: <strong>podrska@tvojdomen.rs</strong></p>
            </div>
        ");
    }
}