<?php

namespace App\Controllers;

use App\Services\LoanCalculatorService;

class LoanController extends BaseController
{
    public function calculate()
    {
        // Vérification de la méthode de la requête
        if (!$this->isPost()) {
            $this->sendError('Méthode non autorisée', 405);
        }

        // Récupération des données de la requête
        $data = $this->getJsonInput();
        $errors = $this->validateRequired($data, ['amount', 'rate', 'duration']);
        if (!empty($errors)) {
            $this->sendError('Données invalides', 400, $errors);
        }

        // Validation des montants
        $amount = $this->validateAmount($data['amount'], 1000, 10000000);
        if ($amount === false) {
            $this->sendError('Montant invalide (doit être entre 1000€ et 10 000 000€)', 400);
        }

        // Validation du taux
        $rate = filter_var($data['rate'], FILTER_VALIDATE_FLOAT);
        if ($rate === false || $rate < 0 || $rate > 20) {
            $this->sendError('Taux d\'intérêt invalide (doit être entre 0% et 20%)', 400);
        }

        // Validation de la durée
        $duration = filter_var($data['duration'], FILTER_VALIDATE_INT);
        if ($duration === false || $duration < 1 || $duration > 30) {
            $this->sendError('Durée invalide (doit être entre 1 et 30 ans)', 400);
        }

        // Utilisation du service métier
        $service = new LoanCalculatorService();
        $result = $service->calculateLoan($amount, $rate, $duration);

        if ($result === null) {
            $this->sendError('Erreur lors du calcul du prêt', 500);
        }

        // Envoi de la réponse
        $this->sendSuccess($result);
    }
} 