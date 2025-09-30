# 🚗 Nuxt-Symfony-Book-Manager 

> Plateforme de management de livres moderne avec Nuxt.js et Symfony

[![Security Scan](https://github.com/username/Nuxt-Symfony-Library/workflows/Security%20&%20Quality%20Checks/badge.svg)](https://github.com/username/Nuxt-Symfony-Library/actions)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## 🏗️ Architecture

```
Nuxt-Symfony-Library/
├── 🎨 frontend/          # Nuxt.js 4 Application
├── ⚙️ backend/           # Symfony 7.3 API
├── 🔒 .github/           # GitHub workflows et templates
├── 📝 .env.example       # Variables d'environnement
├── 🚫 .gitignore         # Configuration Git sécurisée
└── 🔐 SECURITY.md        # Politique de sécurité
```

## 🚀 Stack Technique

### Frontend
- **Nuxt.js 4.1.2** - Framework Vue.js full-stack
- **Tailwind CSS** - Framework CSS utilitaire
- **@nuxt/ui** - Composants UI modernes
- **@nuxt/icon** - Système d'icônes (200k+ icônes)
- **@nuxt/image** - Optimisation d'images
- **@nuxt/fonts** - Optimisation des polices
- **ESLint** - Qualité du code

### Backend
- **Symfony 7.3** - Framework PHP moderne
- **Doctrine ORM 3.5** - Mapping objet-relationnel
- **Security Bundle** - Authentification et autorisation
- **Serializer** - Sérialisation JSON pour API REST
- **Validator** - Validation des données
- **CORS Bundle** - Support des requêtes cross-origin

## 🛠️ Installation

### Prérequis
- **Node.js** 20+ (pour le frontend)
- **PHP** 8.2+ (pour le backend)
- **Composer** (gestionnaire de dépendances PHP)
- **Base de données** (PostgreSQL/MySQL)

### 1. Cloner le projet
```bash
git clone https://github.com/username/Nuxt-Symfony-Library.git
cd Nuxt-Symfony-Library
```

### 2. Configuration des variables d'environnement
```bash
cp .env.example .env
# Modifiez le fichier .env avec vos configurations
```

### 3. Installation du Frontend
```bash
cd frontend
npm install
npm run dev  # Démarre sur http://localhost:3000
```

### 4. Installation du Backend
```bash
cd backend
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start  # Démarre sur http://localhost:8000
```

## 🔒 Sécurité

Ce projet implémente des mesures de sécurité robustes :

### ✅ Pratiques de sécurité
- 🚫 **Fichiers sensibles** exclus du versioning
- 🔐 **Variables d'environnement** sécurisées
- 🛡️ **CORS** configuré pour les requêtes cross-origin
- 📝 **Validation** des données côté backend
- 🔍 **Scans de sécurité** automatisés via GitHub Actions

### 🤖 Automatisation
- **Trivy** - Scan des vulnérabilités
- **TruffleHog** - Détection de secrets
- **npm audit** - Audit des dépendances Node.js
- **Symfony Security Checker** - Vérification des dépendances PHP
- **OWASP Dependency Check** - Analyse globale des dépendances

### 🚨 Signalement de vulnérabilités
Consultez [SECURITY.md](SECURITY.md) pour signaler des problèmes de sécurité.

## 📦 Scripts Disponibles

### Frontend (Nuxt.js)
```bash
cd frontend
npm run dev        # Mode développement
npm run build      # Build de production
npm run preview    # Prévisualisation de production
npm run lint       # Vérification ESLint
npm run lint:fix   # Correction automatique ESLint
```

### Backend (Symfony)
```bash
cd backend
symfony server:start              # Serveur de développement
php bin/console make:entity        # Créer une entité
php bin/console make:controller    # Créer un contrôleur
php bin/console doctrine:migrations:migrate  # Exécuter les migrations
composer require [package]        # Installer un package
```

## 🏃‍♂️ Développement

### Structure des URLs
- **Frontend** : `http://localhost:3000`
- **Backend API** : `http://localhost:8000/api`

### Communication Frontend/Backend
Le frontend Nuxt.js communique avec l'API Symfony via des requêtes HTTP.
La configuration CORS permet les requêtes cross-origin en développement.

## 🤝 Contribution

1. 🍴 **Fork** le projet
2. 🌿 **Créez** votre branche (`git checkout -b feature/AmazingFeature`)
3. ✨ **Commitez** vos changements (`git commit -m 'Add some AmazingFeature'`)
4. 📤 **Push** vers la branche (`git push origin feature/AmazingFeature`)
5. 🔃 **Ouvrez** une Pull Request

Merci de suivre le template de PR fourni dans `.github/pull_request_template.md`.

## 📄 License

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

