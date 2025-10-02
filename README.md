# � Nuxt-Symfony-Library-Demo

> Application de bibliothèque en ligne moderne avec système de gestion des livres, auteurs et catégories

## 🎯 Présentation du Projet

Application web full-stack permettant la gestion d'une bibliothèque en ligne avec :
- 📖 **Consultation** de livres avec détails complets
- 🛒 **Système de panier** pour les utilisateurs
- 👨‍💼 **Interface d'administration** pour la gestion CRUD
- 🔐 **Authentification** et gestion des rôles
- 📱 **Design responsive** et moderne

## 🏗️ Architecture

```
Nuxt-Symfony-Library-Demo/
├── 🎨 frontend/          # Application Nuxt 3 (Interface utilisateur)
│   ├── components/       # Composants Vue réutilisables
│   ├── pages/           # Pages de l'application
│   ├── middleware/      # Middlewares de protection
│   └── types/           # Types TypeScript
├── ⚙️ backend/           # API Symfony 7.3 (Backend)
│   ├── src/             # Code source PHP
│   ├── config/          # Configuration Symfony
│   └── migrations/      # Migrations de base de données
├── � Merise/           # Modélisation de données
└── � docs/             # Documentation du projet
```

## 🚀 Stack Technique Complète

### 🎨 **Frontend - Nuxt 3**
- **Nuxt.js** 4.1.2 - Framework Vue.js full-stack
- **Vue 3** 3.5.22 - Framework JavaScript réactif
- **TailwindCSS** 4.1.13 - Framework CSS utilitaire
- **TypeScript** - Typage statique
- **@nuxt/eslint** - Qualité du code
- **@nuxt/fonts** - Optimisation des polices (Google Fonts)
- **@nuxt/icon** - Système d'icônes
- **@nuxt/image** - Optimisation d'images

### ⚙️ **Backend - Symfony 7.3**
- **PHP** 8.2+ - Langage de programmation
- **Symfony** 7.3 - Framework PHP moderne
- **API Platform** 4.2 - Création d'APIs REST/GraphQL
- **Doctrine ORM** 3.5 - Mapping objet-relationnel
- **Doctrine Migrations** - Gestion des migrations
- **Nelmio CORS Bundle** - Support CORS
- **Symfony Security** - Authentification et autorisation
- **Symfony Serializer** - Sérialisation JSON
- **Symfony Validator** - Validation des données

### 🗄️ **Base de Données**
- **SQLite** (développement)
- **MySQL/PostgreSQL** (production)

## � Comptes de Test

Pour tester l'application, vous pouvez utiliser ces comptes pré-configurés :

### **👨‍💼 Compte Administrateur**
- **Identifiant** : `admin`
- **Mot de passe** : `admin`
- **Rôle** : Administrateur
- **Accès** : Interface d'administration complète (gestion CRUD)

### **👤 Compte Utilisateur Standard**
- **Identifiant** : `Darki`
- **Email** : `vador@mail.com`
- **Mot de passe** : `21ABa!35A6`
- **Rôle** : Utilisateur
- **Accès** : Consultation, panier personnel

## �🛠️ Installation et Configuration

### **Prérequis système**
- **Node.js** ≥ 18.x
- **PHP** ≥ 8.2
- **Composer** (gestionnaire de dépendances PHP)
- **npm** ou **yarn**

### **1. Cloner le projet**
```bash
git clone https://github.com/Kleb0/Nuxt-Symfony-Library-Demo.git
cd Nuxt-Symfony-Library-Demo
```

### **2. Installation du Backend (Symfony)**
```bash
cd backend

# Installation des dépendances
composer install
```

#### **Option A : Import direct de la base de données (RECOMMANDÉ)**
Pour une installation rapide avec toutes les données de test incluses :

```bash
# Créer la base de données vide
php bin/console doctrine:database:create

# Importer le fichier SQL fourni (avec données de test)
# Pour MySQL/MariaDB :
mysql -u [username] -p [database_name] < database_export.sql

# Pour PostgreSQL :
psql -U [username] -d [database_name] -f database_export.sql

# Pour SQLite (par défaut) :
sqlite3 var/data.db < database_export.sql
```

#### **Option B : Installation avec migrations et fixtures**
Pour une installation depuis zéro :

```bash
# Configuration de la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Chargement des données de test
php bin/console doctrine:fixtures:load
```

```bash
# Démarrage du serveur de développement
symfony server:start
# OU
php -S localhost:8000 -t public/
```
**Backend accessible sur :** `http://localhost:8000`

### **3. Installation du Frontend (Nuxt)**
```bash
cd frontend

# Installation des dépendances
npm install

# Démarrage du serveur de développement
npm run dev
```
**Frontend accessible sur :** `http://localhost:3000`

## 🌟 Fonctionnalités

### **👥 Pour tous les utilisateurs**
- ✅ **Consultation** des livres disponibles
- ✅ **Recherche** et filtrage des livres
- ✅ **Détails complets** des livres (auteurs, catégories, résumé, prix)
- ✅ **Inscription** et connexion
- ✅ **Gestion du panier** personnel

### **👨‍💼 Pour les administrateurs**
- ✅ **Gestion CRUD complète** :
  - 📚 **Livres** (création, modification, suppression, upload d'images)
  - ✍️ **Auteurs** (informations complètes)
  - 🏷️ **Catégories** (organisation du catalogue)
- ✅ **Interface d'administration** dédiée
- ✅ **Protection des routes** sensibles

### **🔒 Sécurité**
- ✅ **Authentification JWT** sécurisée
- ✅ **Gestion des rôles** (utilisateur/admin)
- ✅ **Protection des routes** avec middlewares
- ✅ **Validation des données** côté backend
- ✅ **Protection CORS** configurée

## 📱 Interface Utilisateur

### **🎨 Design**
- **Responsive** : Adapté mobile, tablette et desktop
- **TailwindCSS** : Design moderne et cohérent
- **Animations** : Transitions fluides
- **UX optimisée** : Navigation intuitive

### **🧩 Composants**
- **Réutilisables** : Architecture modulaire
- **Typés** : TypeScript pour la robustesse
- **Accessibles** : Bonnes pratiques d'accessibilité

## � Scripts Disponibles

### **Frontend**
```bash
npm run dev        # Serveur de développement
npm run build      # Build de production  
npm run preview    # Aperçu de production
npm run generate   # Génération statique
```

### **Backend**
```bash
symfony server:start                    # Serveur de développement
php bin/console make:entity            # Créer une entité
php bin/console doctrine:migrations:diff  # Créer une migration
php bin/console cache:clear            # Vider le cache
```

## 🌐 APIs Endpoints

### **Authentification**
- `POST /api/auth/login` - Connexion utilisateur
- `POST /api/auth/register` - Inscription

### **Livres**
- `GET /api/books` - Liste des livres
- `GET /api/books/{id}` - Détail d'un livre
- `POST /api/books` - Créer un livre (admin)
- `PUT /api/books/{id}` - Modifier un livre (admin)
- `DELETE /api/books/{id}` - Supprimer un livre (admin)

### **Panier**
- `GET /api/cart/current-user` - Panier de l'utilisateur
- `POST /api/cart/current-user/add` - Ajouter au panier
- `POST /api/cart/current-user/remove` - Retirer du panier

## � Tests et Qualité

### **Qualité du code**
- **ESLint** configuré pour le frontend
- **Conventions** Symfony pour le backend
- **TypeScript** pour le typage statique
- **Git hooks** pour la validation pré-commit

## 📊 Performances

### **Optimisations Frontend**
- **SSR** : Rendu côté serveur avec Nuxt
- **Images** : Optimisation automatique
- **Polices** : Chargement optimisé
- **Code splitting** : Chargement à la demande

### **Optimisations Backend**
- **Doctrine** : ORM optimisé
- **Serializer** : Réponses JSON efficaces
- **Cache** : Système de cache Symfony

## 📈 Compatibilité

### **Navigateurs supportés**
- Chrome ≥ 88
- Firefox ≥ 85  
- Safari ≥ 14
- Edge ≥ 88

### **Systèmes d'exploitation**
- Windows 10+
- macOS 11+
- Linux (Ubuntu 20.04+)

## 🤝 Contribution

Ce projet a été développé dans le cadre d'un test technique. Pour toute suggestion ou amélioration :

1. **Fork** le projet
2. **Créez** votre branche (`git checkout -b feature/nouvelle-fonctionnalite`)
3. **Committez** vos changements (`git commit -m 'Ajout nouvelle fonctionnalité'`)
4. **Push** vers la branche (`git push origin feature/nouvelle-fonctionnalite`)
5. **Ouvrez** une Pull Request

## 📄 Informations du Projet

- **Développeur** : Kleb0
- **Durée de développement** : ~32 heures
- **Période** : Octobre 2025
- **Type** : Test technique

## � License

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

