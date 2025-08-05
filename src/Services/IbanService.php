<?php

namespace App\Services;

class IbanService
{
    private string $apiUrl;

    public function __construct()
    {
        // Construction de l'url
        $this->apiUrl = rtrim(API_CONFIG['openiban']['base_url'], '/') . '/' . API_CONFIG['openiban']['endpoints']['validate'];
    }

    public function validateIban(string $iban): ?array
    {
        // Validation de la structure de l'IBAN
        $structureErrors = $this->validateIbanStructure($iban);
        if (!empty($structureErrors)) {
            return [
                'valid' => false,
                'errors' => $structureErrors,
                'bank_data' => null
            ];
        }
        
        // Suppresion des espaces de l'IBAN
        $cleanIban = str_replace(' ', '', strtoupper($iban));
        
        // Construction de l'url complète
        $url = str_replace('{iban}', $cleanIban, $this->apiUrl);
        
        // Appel API
        $response = @file_get_contents($url);
        if ($response === false) {
            return null;
        }
        $data = json_decode($response, true);
        if (!$data) {
            return null;
        }
        
        // Envoi de la réponse
        return [
            'valid' => $data['valid'] ?? false,
            'bank_data' => [
                'name' => $data['bankData']['name'] ?? null,
                'bic' => $data['bankData']['bic'] ?? null,
                'country' => $data['bankData']['country'] ?? null
            ]
        ];
    }

    private function validateIbanStructure(string $iban): array
    {
        $errors = [];
        $cleanIban = str_replace(' ', '', strtoupper($iban));
        
        // Vérifier la longueur selon le pays
        $country = substr($cleanIban, 0, 2);
        $expectedLengths = [
            'FR' => 27, // France
            'DE' => 22, // Allemagne
            'ES' => 24, // Espagne
            'BE' => 16, // Belgique
            'IT' => 27  // Italie
        ];
        
        if (!isset($expectedLengths[$country])) {
            $errors[] = "Pays non supporté : $country";
        } elseif (strlen($cleanIban) !== $expectedLengths[$country]) {
            $errors[] = "IBAN invalide pour $country (attendu: {$expectedLengths[$country]} caractères, reçu: " . strlen($cleanIban) . ")";
        }
        
        // Vérifier que ce sont bien des caractères alphanumériques
        if (!ctype_alnum($cleanIban)) {
            $errors[] = "L'IBAN ne doit contenir que des lettres et chiffres";
        }
        
        return $errors;
    }
} 