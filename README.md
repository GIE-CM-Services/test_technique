# Test Technique - Développeur PHP Junior
## Application de Services Bancaires

### 📋 Contexte
Vous rejoignez une équipe qui développe des outils internes pour notre banque. Votre mission est de créer une mini-application qui intègre différents services financiers externes.

**Durée estimée : 2 heures**
**À faire : À la maison**

### 🎯 Objectif
Développer une application PHP (sans framework) qui propose 3 services bancaires essentiels :
1. **Convertisseur de devises** en temps réel
2. **Validateur IBAN** avec informations bancaires
3. **Calculateur de prêt immobilier**

### 📦 Ce qui est fourni
- Structure de base du projet avec architecture MVC
- Environnement Docker préconfiguré
- Interface HTML/CSS/JS de base
- Fichier de configuration
- Exemple de contrôleur de base

### 🚀 Installation

#### Option 1 : Avec Docker (recommandé)
```bash
# Cloner le repository
git clone https://github.com/GIE-CM-Services/test_technique.git
cd test_technique

# Lancer l'environnement
docker-compose up -d

# L'application sera accessible sur http://localhost:8080
```

#### Option 2 : Sans Docker
Requirements :
- PHP 7.4 ou supérieur
- Extension JSON activée

```bash
# Depuis la racine du projet
cd public
php -S localhost:8080

# L'application sera accessible sur http://localhost:8080
```

### 📝 Spécifications détaillées

#### 1. Module de conversion de devises (40%)

**Fonctionnalités attendues :**
- Sélection de devise source et devise cible
- Saisie d'un montant à convertir
- Affichage du résultat via AJAX (sans rechargement de page)
- Gestion des erreurs (API indisponible, montant invalide)
- Minimum 3 devises : EUR, USD, GBP

**API à utiliser :**
```
URL : https://api.exchangeratesapi.io/v1/latest
Méthode : GET
Paramètres :
  - clé API : abacc0bb8fbf88e03fd75b6e1551bca3
  - base : devise de base (ex: EUR)
  - symbols : devises cibles (ex: USD,GBP)
Exemple : https://api.exchangeratesapi.io/v1/latest?base=EUR&symbols=USD,GBP
```

**Points d'attention :**
- ✅ Validation côté serveur obligatoire
- ✅ Formatage des montants (2 décimales)
- ✅ Gestion du cache (bonus)
- ❌ Ne pas faire confiance aux données client

#### 2. Validateur IBAN (30%)

**Fonctionnalités attendues :**
- Validation de la structure IBAN
- Récupération des informations bancaires (nom, BIC)
- Formatage de l'IBAN pour l'affichage (groupes de 4)
- Support multi-pays (au moins FR, DE, ES)

**API à utiliser :**
```
URL : https://openiban.com/validate/{iban}
Méthode : GET
Paramètres :
  - getBIC=true (pour récupérer le BIC)
Exemple : https://openiban.com/validate/DE89370400440532013000?getBIC=true
```

**IBANs de test fournis :**
```
France    : FR1420041010050500013M02606
Allemagne : DE89370400440532013000
Espagne   : ES9121000418450200051332
Belgique  : BE68539007547034
Italie    : IT60X0542811101000000123456
```

#### 3. Calculateur de prêt immobilier (30%)

**Fonctionnalités attendues :**
- Saisie : montant, taux annuel, durée en années
- Calcul de la mensualité
- Affichage du coût total et des intérêts
- Export des résultats (JSON)

**Formule mathématique :**
```php
// Mensualité = P × [r(1 + r)^n] / [(1 + r)^n - 1]
// Où :
// P = Montant principal (emprunté)
// r = Taux d'intérêt mensuel (taux annuel / 12 / 100)
// n = Nombre total de paiements (années × 12)

function calculerMensualite($montant, $tauxAnnuel, $dureeAnnees) {
    $tauxMensuel = $tauxAnnuel / 12 / 100;
    $nombreMois = $dureeAnnees * 12;

    if ($tauxMensuel == 0) {
        return $montant / $nombreMois;
    }

    $mensualite = $montant *
        ($tauxMensuel * pow(1 + $tauxMensuel, $nombreMois)) /
        (pow(1 + $tauxMensuel, $nombreMois) - 1);

    return round($mensualite, 2);
}
```

### 🏗️ Architecture attendue

```
test-technique-php-bancaire/
├── public/
│   ├── index.php              # Point d'entrée unique
│   └── assets/
│       ├── css/style.css      # Styles fournis
│       └── js/app.js          # JavaScript fourni
│
├── src/
│   ├── Controllers/           # Vos contrôleurs
│   │   ├── BaseController.php # Fourni comme exemple
│   │   ├── CurrencyController.php    # À créer
│   │   ├── IbanController.php        # À créer
│   │   └── LoanController.php        # À créer
│   │
│   ├── Services/              # Logique métier
│   │   ├── CurrencyService.php       # À créer
│   │   ├── IbanService.php           # À créer
│   │   └── LoanCalculatorService.php # À créer
│   │
│   └── Utils/                 # Classes utilitaires
│       └── HttpClient.php     # À créer (optionnel)
│
├── config/
│   └── config.php             # Configuration fournie
│
├── cache/                     # Cache des appels API (optionnel)
├── logs/                      # Logs d'erreurs (optionnel)
└── docker-compose.yml         # Environnement Docker
```

### 🔧 Routing simple à implémenter

Dans `public/index.php`, vous devez implémenter un routeur simple :

```php
// Exemple de routing basique
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/api/convert':
        // Instancier et appeler CurrencyController
        break;
    case '/api/validate-iban':
        // Instancier et appeler IbanController
        break;
    case '/api/calculate-loan':
        // Instancier et appeler LoanController
        break;
    default:
        // Afficher la page d'accueil
        break;
}
```

### ✅ Critères d'évaluation

#### 1. Qualité du code (40%)
- Organisation MVC respectée
- Code lisible et commenté
- Respect des conventions PSR
- DRY (Don't Repeat Yourself)
- Gestion d'erreurs appropriée

#### 2. Sécurité (30%)
- ✅ Validation des entrées côté serveur
- ✅ Protection contre XSS (htmlspecialchars)
- ✅ Headers HTTP appropriés
- ✅ Pas de données sensibles exposées
- ❌ Pas d'eval() ou de fonctions dangereuses

#### 3. Fonctionnalités (20%)
- Toutes les features implémentées
- Gestion des cas d'erreur
- Interface fonctionnelle
- Respect des spécifications

#### 4. Bonus (10%)
- Tests unitaires
- Cache intelligent des résultats API
- Documentation du code (PHPDoc)
- Interface responsive
- Fonctionnalités supplémentaires

### 🛠️ Conseils techniques

#### Appels HTTP en PHP
```php
// Méthode simple avec gestion d'erreur
function callApi($url) {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 5,
            'ignore_errors' => true,
            'header' => [
                'Accept: application/json',
                'User-Agent: PHP Test Bancaire'
            ]
        ]
    ]);

    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        throw new Exception('Erreur lors de l\'appel API');
    }

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Réponse JSON invalide');
    }

    return $data;
}
```

#### AJAX en JavaScript vanilla
```javascript
// Fonction utilitaire pour les appels AJAX
async function apiCall(endpoint, data = null) {
    try {
        const options = {
            method: data ? 'POST' : 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        };

        if (data) {
            options.body = JSON.stringify(data);
        }

        const response = await fetch(endpoint, options);
        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Erreur serveur');
        }

        return result;
    } catch (error) {
        console.error('Erreur API:', error);
        throw error;
    }
}
```

### 📚 Ressources utiles
- [PHP The Right Way](https://phptherightway.com/)
- [PSR-1 Basic Coding Standard](https://www.php-fig.org/psr/psr-1/)
- [PSR-4 Autoloading Standard](https://www.php-fig.org/psr/psr-4/)
- [OWASP PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)

### 🚫 Ce qu'il ne faut PAS faire
- ❌ Utiliser des frameworks (Laravel, Symfony, Slim...)
- ❌ Utiliser jQuery ou autres librairies JS lourdes
- ❌ Copier/coller du code sans le comprendre
- ❌ Mettre la logique métier dans les vues
- ❌ Ignorer la validation côté serveur
- ❌ Stocker des données sensibles en clair

### 💡 Points bonus appréciés
- ✨ Implémentation d'un système de cache pour les taux de change
- ✨ Historique des dernières conversions en session
- ✨ Export CSV/PDF du calcul de prêt
- ✨ Mode sombre sur l'interface
- ✨ Gestion multi-langues (FR/EN)
- ✨ Tests unitaires avec PHPUnit
- ✨ Documentation API avec annotations

### 📤 Rendu attendu

1. **Code source complet** en archive ZIP ou lien Git
2. **Instructions** si vous avez modifié l'installation
3. **Documentation** des choix techniques (1 page max)
4. **Compte-rendu** des difficultés rencontrées

### ⏱️ Gestion du temps recommandée

- **30 min** : Analyse et architecture
- **45 min** : Module de conversion
- **30 min** : Validateur IBAN
- **30 min** : Calculateur de prêt
- **15 min** : Tests et documentation
- **10 min** : Révision et packaging

### ❓ FAQ

**Q : Puis-je utiliser Composer ?**
R : Oui, mais uniquement pour l'autoloading PSR-4, pas pour ajouter des dépendances.

**Q : Comment gérer les clés API ?**
R : Les APIs fournies ne nécessitent pas de clé. Si vous en ajoutez, utilisez le fichier config.php.

**Q : Dois-je gérer l'authentification ?**
R : Non, ce n'est pas nécessaire pour ce test.

**Q : Puis-je modifier l'interface fournie ?**
R : Oui, tant que les fonctionnalités de base restent accessibles.

**Q : Comment gérer les erreurs réseau ?**
R : Affichez un message d'erreur clair à l'utilisateur et loggez l'erreur côté serveur.

---

**Bon courage !** 🚀

Si vous avez des questions sur l'énoncé, contactez : [dev@credit-municipal-services.fr]

*Note : Ce test évalue vos compétences techniques actuelles. Nous valorisons autant la qualité du code que votre capacité à structurer une application maintenable.*
