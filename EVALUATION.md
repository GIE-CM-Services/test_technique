# Guide d'évaluation - Test Technique PHP

## Grille d'évaluation détaillée

### 1. Architecture et Organisation (30 points)

#### Structure MVC (15 points)
- [ ] **Excellent (15 pts)** : Séparation claire Controllers/Services/Views, utilisation de namespaces
- [ ] **Bon (10 pts)** : Structure organisée mais quelques mélanges de responsabilités
- [ ] **Moyen (5 pts)** : Tentative de structure mais logique métier dans les vues
- [ ] **Insuffisant (0 pts)** : Tout le code dans un seul fichier ou structure chaotique

#### Réutilisabilité du code (15 points)
- [ ] **Excellent (15 pts)** : Fonctions/classes réutilisables, DRY appliqué
- [ ] **Bon (10 pts)** : Quelques duplications mais effort de factorisation
- [ ] **Moyen (5 pts)** : Beaucoup de code dupliqué
- [ ] **Insuffisant (0 pts)** : Copy/paste systématique

### 2. Sécurité (25 points)

#### Validation des entrées (10 points)
- [ ] **Excellent (10 pts)** : Validation côté serveur complète, types vérifiés
- [ ] **Bon (7 pts)** : Validation présente mais quelques cas manqués
- [ ] **Moyen (4 pts)** : Validation basique uniquement
- [ ] **Insuffisant (0 pts)** : Aucune validation côté serveur

#### Protection XSS (10 points)
- [ ] **Excellent (10 pts)** : htmlspecialchars() systématique, Content-Type correct
- [ ] **Bon (7 pts)** : Protection présente mais quelques oublis
- [ ] **Moyen (4 pts)** : Protection partielle
- [ ] **Insuffisant (0 pts)** : Aucune protection XSS

#### Gestion des erreurs API (5 points)
- [ ] **Excellent (5 pts)** : Try/catch, timeouts, gestion des codes HTTP
- [ ] **Bon (3 pts)** : Gestion basique des erreurs
- [ ] **Insuffisant (0 pts)** : Aucune gestion d'erreur

### 3. Qualité du code PHP (20 points)

#### Standards et conventions (10 points)
- [ ] **Excellent (10 pts)** : PSR respecté, code cohérent et lisible
- [ ] **Bon (7 pts)** : Code propre avec quelques incohérences
- [ ] **Moyen (4 pts)** : Code fonctionnel mais difficile à lire
- [ ] **Insuffisant (0 pts)** : Code illisible, pas de standards

#### Commentaires et documentation (10 points)
- [ ] **Excellent (10 pts)** : PHPDoc, commentaires pertinents
- [ ] **Bon (7 pts)** : Commentaires présents sur les parties complexes
- [ ] **Moyen (4 pts)** : Peu de commentaires
- [ ] **Insuffisant (0 pts)** : Aucun commentaire

### 4. Fonctionnalités (20 points)

#### Convertisseur de devises (8 points)
- [ ] Appel API fonctionnel (3 pts)
- [ ] Gestion des erreurs (2 pts)
- [ ] Formatage correct des montants (2 pts)
- [ ] Interface utilisateur intuitive (1 pt)

#### Validateur IBAN (6 points)
- [ ] Validation fonctionnelle (2 pts)
- [ ] Récupération infos bancaires (2 pts)
- [ ] Formatage IBAN (1 pt)
- [ ] Gestion des erreurs (1 pt)

#### Calculateur de prêt (6 points)
- [ ] Calcul correct (3 pts)
- [ ] Affichage détaillé (2 pts)
- [ ] Validation des limites (1 pt)

### 5. Points bonus (5 points)

- [ ] **Cache des résultats API** (1 pt)
- [ ] **Tests unitaires** (1 pt)
- [ ] **Interface responsive** (1 pt)
- [ ] **Fonctionnalités supplémentaires** (1 pt)
- [ ] **Documentation technique** (1 pt)

## Red Flags (élimination directe)

### Sécurité critique
- [ ] Injection SQL (si BDD utilisée sans requêtes préparées)
- [ ] Eval() ou similaire sur des entrées utilisateur
- [ ] Include/require avec des variables utilisateur
- [ ] Exposition de données sensibles (clés API dans le JS)

### Mauvaises pratiques graves
- [ ] Suppression des warnings avec @ systématique
- [ ] Variables globales partout
- [ ] Mélange HTML/PHP/JS dans le même fichier
- [ ] Aucune gestion d'erreur

## Questions à poser lors de l'entretien

### Questions techniques
1. "Expliquez votre approche pour gérer les erreurs d'API"
2. "Comment avez-vous sécurisé l'application contre les attaques XSS ?"
3. "Pourquoi avez-vous choisi cette architecture ?"
4. "Comment amélioreriez-vous les performances de l'application ?"

### Questions sur le code
1. "Montrez-moi la partie du code dont vous êtes le plus fier"
2. "Quelle partie a été la plus difficile et pourquoi ?"
3. "Si vous aviez plus de temps, que changeriez-vous ?"

### Questions pratiques
1. "Comment testeriez-vous cette application ?"
2. "Comment géreriez-vous le déploiement en production ?"
3. "Comment ajouteriez-vous une nouvelle devise ?"

## Profils types

### Candidat Excellent (85-100 points)
- Code structuré et sécurisé
- Gestion d'erreurs complète
- Bonus implémentés
- Prêt pour la codebase complexe

### Candidat Bon (70-84 points)
- Fonctionnalités complètes
- Quelques faiblesses mineures
- Potentiel avec formation
- Autonome sur des tâches simples

### Candidat Moyen (50-69 points)
- Fonctionnel mais brouillon
- Manque de rigueur
- Nécessite encadrement important
- Risque sur codebase complexe

### Candidat Insuffisant (<50 points)
- Fonctionnalités incomplètes
- Problèmes de sécurité
- Code difficile à maintenir
- Pas prêt pour le poste

## Conseils pour l'évaluation

1. **Tester réellement l'application** : Vérifier tous les cas (erreurs, limites, etc.)

2. **Lire le code en détail** : La structure est aussi importante que le fonctionnel

3. **Valoriser l'effort** : Un junior qui documente et structure mérite considération

4. **Considérer l'évolutivité** : Le code est-il facilement extensible ?

5. **Évaluer la compréhension** : Le candidat a-t-il compris les enjeux bancaires ?

## Template de feedback

```
CANDIDAT: [Nom]
DATE: [Date]
SCORE: [XX/100]

POINTS FORTS:
-
-
-

POINTS D'AMÉLIORATION:
-
-
-

RECOMMANDATION: [ACCEPTÉ/REFUSÉ/SECOND ENTRETIEN]

COMMENTAIRES:
[Détails sur la décision]
```
