<?php

namespace App\Controllers;

use App\Services\IbanService;

class IbanController extends BaseController
{
    public function validate()
    {
        // Vérification de la méthode de la requête
        if (!$this->isPost()) {
            $this->sendError('Méthode non autorisée', 405);
        }

        // Récupération des données de la requête
        $data = $this->getJsonInput();
        $errors = $this->validateRequired($data, ['iban']);
        if (!empty($errors)) {
            $this->sendError('Données invalides', 400, $errors);
        }

        // Validation de l'IBAN
        $iban = trim($data['iban']);
        if (empty($iban)) {
            $this->sendError('IBAN requis', 400);
        }

        // Utilisation du service métier
        $service = new IbanService();
        $result = $service->validateIban($iban);
        
        if ($result === null) {
            $this->sendError('Erreur lors de la validation de l\'IBAN', 502);
        }

        if (isset($result['errors']) && !empty($result['errors'])) {
            $this->sendError('Structure IBAN invalide', 400, $result['errors']);
        }

        // Envoi de la réponse
        $this->sendSuccess($result);
    }
} 