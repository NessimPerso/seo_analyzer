# SEO Content Analyzer

Outil d'analyse automatisée de contenu qui évalue des textes selon des consignes éditoriales et clients spécifiques. Utilise ChatGPT pour analyser le contenu et retourne un score sur 10 avec un feedback détaillé.

## Fonctionnalités principales

- Formulaire simple pour soumettre consignes éditoriales, directives clients et texte à analyser
- Analyse automatique via OpenAI ChatGPT
- Évaluation sur 4 critères : orthographe, respect des consignes édito, respect des consignes client, pertinence et vérification des informations
- Score global sur 10 et décision automatique (acceptable ou à corriger)
- Feedback détaillé pour chaque critère avec recommandations

## Installation rapide

### Prérequis
- PHP 8.x avec curl activé
- Node.js 14+
- Clé API OpenAI

### Backend

Allez dans le dossier backend et créez un fichier .env :
```
OPENAI_API_KEY=sk-proj-votre-clé-ici
```

Lancez le serveur PHP depuis backend/public :
```bash
cd backend/public
php -S localhost:8000 router.php
```

### Frontend

Installez et lancez le serveur Node depuis le dossier frontend :
```bash
cd frontend
npm install
npm start
```

Accédez à l'application sur http://localhost:3000

## Architecture

Backend PHP avec trois couches : contrôleur pour l'orchestration, services pour OpenAI et scoring, configuration pour les variables d'environnement.

Frontend HTML/CSS/JavaScript vanille sans dépendances. API communication par requêtes POST JSON.

## Utilisation

Remplissez les trois champs du formulaire, cliquez sur Analyser, et consultez les résultats affichés avec le score global et les quatre critères détaillés.

