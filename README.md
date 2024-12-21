# Taskify_API
# 🛠️ Gestion des Tâches Individuelles - API REST avec Laravel Sanctum

## 🚀 Présentation du Projet
Ce projet consiste en le développement d'une **API REST sécurisée** pour la gestion des tâches individuelles, permettant aux utilisateurs authentifiés de **créer, modifier, marquer comme complétées ou supprimer leurs tâches personnelles**. L'API garantit la sécurité des données grâce à l'authentification avec **Laravel Sanctum** et utilise des politiques d'accès pour s'assurer que chaque utilisateur ne peut gérer que ses propres tâches.

L'objectif est de fournir une API robuste, fiable et bien documentée, tout en mettant l'accent sur la sécurité et la facilité d'utilisation pour les développeurs.

---

## 📋 Fonctionnalités Principales

### 🔐 Authentification et Sécurité
- Authentification des utilisateurs via **Laravel Sanctum**.
- Génération de jetons d'accès pour sécuriser les requêtes.

### 📝 Gestion des Tâches
- **Création** de nouvelles tâches associées à l'utilisateur authentifié.
- **Modification** des tâches existantes.
- **Suppression** de tâches spécifiques.
- **Marquage** des tâches comme complétées ou incomplètes.

### 🔒 Politiques d'Accès
- Limitation de l'accès aux tâches de l'utilisateur authentifié uniquement.
- Mise en place de règles pour s'assurer que chaque utilisateur ne peut gérer que ses propres données.

### ✅ Tests et Documentation
- **Tests unitaires** pour garantir la fiabilité des fonctionnalités.
- **Tests sur Postman** pour valider l'API dans divers scénarios.
- Documentation complète avec **Swagger**.

---

## 🔧 Technologies Utilisées

### Backend
- **Framework :** Laravel 10
- **Authentification :** Laravel Sanctum
- **Langage :** PHP 8
- **Base de Données :** MySQL
- **Tests :** PHPUnit, Postman
- **Documentation :** Swagger

---

## 🚀 Instructions d'Installation

### Prérequis
- PHP 8.0+ avec Composer installé.
- MySQL 5.7+.
- Postman pour tester les endpoints.

### Étapes
1. **Cloner le Repository :**
   ```bash
   git clone https://github.com/CHERKAOUIfatimazahra/Taskify_API.git
