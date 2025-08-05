<?php

namespace App\Controllers;

use App\Services\CurrencyService;

class CurrencyController extends BaseController
{
    public function convert()
    {
        // Vérification de la méthode de la requête
        if (!$this->isPost()) {
            $this->sendError('Méthode non autorisée', 405);
        }

        // Récupération des données de la requête
        $data = $this->getJsonInput();
        $errors = $this->validateRequired($data, ['amount', 'from_currency', 'to_currency']);
        if (!empty($errors)) {
            $this->sendError('Données invalides', 400, $errors);
        }

        // Validation du montant
        $amount = $this->validateAmount($data['amount']);
        if ($amount === false) {
            $this->sendError('Montant invalide', 400);
        }

        // Conversion des devises en majuscules
        $from = strtoupper($data['from_currency']);
        $to = strtoupper($data['to_currency']);

        // Utilisation du service métier
        $service = new CurrencyService();
        $rate = $service->getExchangeRate($from, $to);
        if ($rate === null) {
            $this->sendError('Erreur lors de la récupération du taux de change', 502);
        }

        // Conversion du montant
        $converted = $amount * $rate;

        // Envoi de la réponse
        $this->sendSuccess([
            'converted_amount' => $converted,
            'exchange_rate' => $rate,
            'from_currency' => $from,
            'to_currency' => $to
        ]);
    }
} 