<?php
session_start();
require_once '../config/config.php';

// Routing
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/api/convert':
        (new \App\Controllers\CurrencyController())->convert();
        break;
    case '/api/iban':
        (new \App\Controllers\IbanController())->validate();
        break;
    case '/api/loan':
        (new \App\Controllers\LoanController())->calculate();
        break;
    default:
        // Afficher la page d'accueil
        break;
}
?>
<!-- Le HTML de l'application commence ici -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CM Services - Test Technique</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>🏦 CM Services</h1>
            <p>Application de test technique</p>
        </div>
    </header>

    <main class="container">
        <button id="toggle-dark-mode" class="btn btn-secondary mt-3 mb-2">
            Mode sombre
        </button>

        <!-- Navigation par onglets -->
        <nav class="tabs">
            <button class="tab-button active" data-tab="converter">
                💱 Convertisseur
            </button>
            <button class="tab-button" data-tab="iban">
                🔍 Validateur IBAN
            </button>
            <button class="tab-button" data-tab="loan">
                🏠 Calculateur de prêt
            </button>
        </nav>

        <!-- Contenu des onglets -->
        <div class="tab-content">
            <!-- Onglet Convertisseur -->
            <div id="converter" class="tab-pane active">
                <h2>Convertisseur de devises</h2>
                <form id="converter-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="amount">Montant</label>
                            <input type="number"
                                id="amount"
                                name="amount"
                                step="0.01"
                                min="0"
                                placeholder="100.00"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="from-currency">De</label>
                            <select id="from-currency" name="from_currency" required>
                                <option value="EUR">EUR - Euro</option>
                                <option value="USD">USD - Dollar US</option>
                                <option value="GBP">GBP - Livre Sterling</option>
                                <option value="CHF">CHF - Franc Suisse</option>
                                <option value="JPY">JPY - Yen</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="to-currency">Vers</label>
                            <select id="to-currency" name="to_currency" required>
                                <option value="USD">USD - Dollar US</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="GBP">GBP - Livre Sterling</option>
                                <option value="CHF">CHF - Franc Suisse</option>
                                <option value="JPY">JPY - Yen</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Convertir
                    </button>
                </form>

                <div id="converter-result" class="result-box" style="display: none;">
                    <!-- Résultat de la conversion -->
                </div>
            </div>

            <!-- Onglet Validateur IBAN -->
            <div id="iban" class="tab-pane">
                <h2>Validateur IBAN</h2>
                <form id="iban-form">
                    <div class="form-group">
                        <label for="iban-input">Numéro IBAN</label>
                        <input type="text"
                            id="iban-input"
                            name="iban"
                            placeholder="FR1420041010050500013M02606"
                            maxlength="34"
                            required>
                        <small>Exemples : FR1420041010050500013M02606, DE89370400440532013000</small>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">
                        Valider
                    </button>
                </form>

                <div id="iban-result" class="result-box" style="display: none;">
                    <!-- Résultat de la validation -->
                </div>
            </div>

            <!-- Onglet Calculateur de prêt -->
            <div id="loan" class="tab-pane">
                <h2>Calculateur de prêt immobilier</h2>
                <form id="loan-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="loan-amount">Montant emprunté (€)</label>
                            <input type="number"
                                id="loan-amount"
                                name="amount"
                                step="1000"
                                min="0"
                                placeholder="200000"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="interest-rate">Taux d'intérêt annuel (%)</label>
                            <input type="number"
                                id="interest-rate"
                                name="rate"
                                step="0.01"
                                min="0"
                                max="20"
                                placeholder="3.5"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="loan-duration">Durée (années)</label>
                            <input type="number"
                                id="loan-duration"
                                name="duration"
                                step="1"
                                min="1"
                                max="30"
                                placeholder="20"
                                required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Calculer
                    </button>
                    <button type="button" id="export-csv" class="btn btn-secondary ml-3" disabled>
                        Exporter en CSV
                    </button>
                </form>

                <div id="loan-result" class="result-box" style="display: none;">
                    <!-- Résultat du calcul -->
                </div>
            </div>
        </div>

        <!-- Zone de messages d'erreur globale -->
        <div id="error-message" class="error-message" style="display: none;">
            <!-- Messages d'erreur -->
        </div>
    </main>

    <footer>
        <div class="container">
            <p>Test technique GIE CM Services - <?php echo date('Y'); ?></p>
        </div>
    </footer>

    <script src="assets/js/app.js"></script>
</body>

</html>
