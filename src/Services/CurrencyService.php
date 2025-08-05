<?php

namespace App\Services;

class CurrencyService
{
    private string $apiUrl;
    private ?string $apiKey;

    public function __construct()
    {
        // Construction de l'url
        $this->apiUrl = rtrim(API_CONFIG['exchange_rates']['base_url'], '/') . '/' . API_CONFIG['exchange_rates']['endpoints']['latest'];
        $this->apiKey = API_CONFIG['exchange_rates']['api_key'];
    }

    public function getExchangeRate(string $from, string $to): ?float
    {
        // Construction de l'url complète
        $url = $this->apiUrl . "?base={$from}&symbols={$to}";
        if ($this->apiKey) {
            $url .= "&access_key={$this->apiKey}";
        }

        // Appel API
        $response = @file_get_contents($url);
        if ($response === false) {
            return null;
        }
        $data = json_decode($response, true);
        if (!isset($data['rates'][$to])) {
            return null;
        }

        // Envoi de la réponse
        return (float)$data['rates'][$to];
    }
} 