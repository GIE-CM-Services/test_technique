<?php

namespace App\Controllers;

/**
 * Contrôleur de base
 *
 * Classe abstraite fournissant les méthodes communes
 * à tous les contrôleurs de l'application
 */
abstract class BaseController
{
    /**
     * Vérifie si la requête est de type POST
     *
     * @return bool
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Récupère les données JSON de la requête
     *
     * @return array
     */
    protected function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendError('Données JSON invalides', 400);
        }

        return $data ?? [];
    }

    /**
     * Envoie une réponse de succès
     *
     * @param array $data
     * @param int $statusCode
     */
    protected function sendSuccess(array $data, int $statusCode = 200): void
    {
        $response = [
            'success' => true,
            'data' => $data,
            'timestamp' => date('c')
        ];

        jsonResponse($response, $statusCode);
    }

    /**
     * Envoie une réponse d'erreur
     *
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     */
    protected function sendError(string $message, int $statusCode = 400, array $errors = []): void
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => date('c')
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        jsonResponse($response, $statusCode);
    }

    /**
     * Valide les données requises
     *
     * @param array $data
     * @param array $requiredFields
     * @return array Erreurs de validation
     */
    protected function validateRequired(array $data, array $requiredFields): array
    {
        $errors = [];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[$field] = "Le champ '$field' est requis.";
            }
        }

        return $errors;
    }

    /**
     * Effectue une requête HTTP
     *
     * @param string $url
     * @param array $options
     * @return array|false
     */
    protected function httpRequest(string $url, array $options = [])
    {
        $defaultOptions = [
            'http' => [
                'method' => 'GET',
                'timeout' => 10,
                'ignore_errors' => true,
                'header' => [
                    'User-Agent: PHP Test CM Services/1.0',
                    'Accept: application/json'
                ]
            ]
        ];

        // Fusionner les options
        if (!empty($options)) {
            $defaultOptions = array_merge_recursive($defaultOptions, $options);
        }

        $context = stream_context_create($defaultOptions);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            logError("Erreur HTTP lors de l'appel à: $url");
            return false;
        }

        // Vérifier le code de statut HTTP
        $httpCode = 200;
        if (isset($http_response_header)) {
            foreach ($http_response_header as $header) {
                if (preg_match('/^HTTP\/\d\.\d\s+(\d+)/', $header, $matches)) {
                    $httpCode = (int)$matches[1];
                    break;
                }
            }
        }

        // Décoder la réponse JSON
        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            logError("Erreur de décodage JSON pour: $url");
            return false;
        }

        return [
            'status_code' => $httpCode,
            'data' => $data
        ];
    }

    /**
     * Nettoie et valide un montant
     *
     * @param mixed $amount
     * @param float $min
     * @param float $max
     * @return float|false
     */
    protected function validateAmount($amount, float $min = 0.01, float $max = PHP_FLOAT_MAX)
    {
        $cleaned = str_replace([' ', ','], ['', '.'], $amount);
        $float = filter_var($cleaned, FILTER_VALIDATE_FLOAT);

        if ($float === false || $float < $min || $float > $max) {
            return false;
        }

        return round($float, 2);
    }
}

/**
 * Exemple d'utilisation dans un contrôleur spécifique :
 *
 * class CurrencyController extends BaseController
 * {
 *     public function convert()
 *     {
 *         if (!$this->isPost()) {
 *             $this->sendError('Méthode non autorisée', 405);
 *         }
 *
 *         $data = $this->getJsonInput();
 *
 *         // Validation
 *         $errors = $this->validateRequired($data, ['amount', 'from_currency', 'to_currency']);
 *         if (!empty($errors)) {
 *             $this->sendError('Données invalides', 400, $errors);
 *         }
 *
 *         // Logique métier...
 *
 *         $this->sendSuccess([
 *             'converted_amount' => $result,
 *             'exchange_rate' => $rate
 *         ]);
 *     }
 * }
 */
