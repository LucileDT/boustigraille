# Boustigraille
[![License : GNU AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](https://www.gnu.org/licenses/agpl-3.0) [![License : OBdL v1.0](https://img.shields.io/badge/licence-ODbL%20v1.0-blue)](https://opendatacommons.org/licenses/odbl/1-0/)

Décompte des macronutriments de repas préenregistrés et suivi nutritionnel.

# Licences

## Application

L'application est disponible sous licence [AGPL v3](https://www.gnu.org/licenses/agpl-3.0).

## Base de données

La base de données est disponible sous licence [ODbL v1.0](https://opendatacommons.org/licenses/odbl/1-0/).

# Fonctionnalités

## Réalisées
- enregistrement d'ingrédients (notamment nom, marque, valeurs nutritionelles)
- import des valeurs nutritionnelles pour un ingrédient depuis l'URL du produit sur Open Food Facts
- enregistrement de recettes (ingrédients, valeurs nutritionnelles, procédure de préparation)
- enregistrement des valeurs nutritionnelles de référence pour un compte utilisateur
- enregistrement de liste de repas (liste de recettes (suggestion de recettes), nombre de parts par recette)
- génération de liste de courses (ingrédients et quantités, triés dans un ordre pertinent)
- recherche de recettes via leur titre

## Prévues

- recherche de recettes via leurs valeurs nutritionnelles (en fonction des valeurs nutritionnelles de références du compte utilisateur connecté)
- suivi du poids pour un compte utilisateur

# Installation

## Pré-requis

- php >= **8.1.0**
    - Installer les extensions php listées dans le fichier **composer.json**
- symfony cli
    - https://symfony.com/download
- composer **2**
    - https://getcomposer.org/download
- npm >= **18.0**
    - https://docs.npmjs.com/downloading-and-installing-node-js-and-npm
- postgresql server >= **17.9**
    - Ainsi qu'un utilisateur de votre choix.
    - Il est possible de se connecter avec l'utilisateur root mais c'est une mauvaise pratique que nous ne recommmandons pas
- git

## Marche à suivre

### 1. Cloner le projet

`git clone git@github.com:LucileDT/boustigraille.git` (ssh)

`git clone https://github.com/LucileDT/boustigraille.git` (https)

### 2. Définir les variable d'environnement

1. Copier le fichier caché **.env** vers **.env.local** à la racine du projet

`cp .env .env.local`

2. Générer une valeur pour le **APP_SECRET** avec l'outil cryptographique de votre choix (par exemple un hash SHA256)

3. Remplacer la valeur de la variable **APP_SECRET** avec la chaine générée à l'étape précédente, dans le fichier **.env.local**. Exemple:

`APP_SECRET=9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08`

4. Éditer la valeur de la variable **APP_ENV** en `APP_ENV=DEV`

5. Décommenter la ligne `# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"`

6. Remplacer les placeholders de la chaîne de connection à postgresql avec les valeurs correspondant à votre installation

- **DB User**: *app*
- **DB Password**: *!ChangeMe!*
- **DB Host**: *127.0.0.1*
- **DB Port**: *5432*
- **DB Name**: *app*
- **serverVersion**: *16*

### 3. Installer les dépendances php

À la racine du projet, installer les dépendances php via composer:

`composer install`

⚠️ S'il manque des extensions php, un message d'erreur explicite s'affichera (voir [Pré-requis](#pré-requis))

### 4. Mise en place de la BDD

À la racine du projet, lancer les commandes suivantes:

1. Si ce n'est pas fait, créer la base de données

`bin/console doctrine:database:create`

⚠️ Si l'utilisateur postgresql n'a pas les droits **CREATE DATABASE**, une erreur surviendra.

⚠️ De la même manière, si la chaîne de connection postgres est mal formatée, une erreur sera levée

2. Exécuter les migrations doctrine

`bin/console doctrine:migrations:migrate` puis `yes`

3. Charger les fixtures (données de développement)

`bin/console doctrine:fixtures:load` puis `yes`

### 5. Compilation des sources front

À la racine du projet, lancer les commandes suivantes:

`npm install`

`npm run dev`

## Vérifier son installation

1. Lancer le serveur de développement Symfony

`symfony server:start`

2. Ouvrir http://localhost:8000/recipe pour s'assurer que le site s'affiche correctement

3. Happy coding!

# Démarrer son environnement de dev

1. À la racine du projet, lancer le serveur Symfony

`symfony server:start`

2. Dans un nouvel onglet de terminal, lancer webpack en mode HotModuleReload

`npm run dev-server`