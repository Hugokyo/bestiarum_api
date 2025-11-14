# README du projet ARCHI_MONSTRE
bienvenue sur le repository du projet API_PHP_ARCHI_MONSTRE. Ce projet a pour but de créer une API RESTful en PHP permettant la gestion d'une base de données de monstres, avec des fonctionnalités d'authentification, de création de monstres hybrides, et de gestion de combats entre monstres. Ce projet a été réalisé dans le cadre d'un cours d'architecture logicielle à `MyDigitalSchool CAEN` (donc il se peux que l'API ne soit pas parfaite). Sur cette documentation, vous allez retrouver différentes rubriques pour vous expliquer comment installer le projet et faire fonctionner au mieux l'API. Pour cela :

## Table des matières
- [Installation](#installation)
- [API Routes](#API-Routes)
- [Documentation des fonctions principales](#documentation-des-fonctions-principales)
- [Réponses API](#réponses-api)
- [Documentation technique](#documentation-technique)
- [Documentation utilisateurs](#documentation-utilisateurs)

## Installation

### installation de composer

Pour avoir un bon fonctionnement de note api, veillez a installer les différent package 

```bash
composer install 
```

### installation du fichier de la bdd

```bash
├───includes
│   ├───controllers
│   │   ├───auth.controller.php
│   │   ├───hybrids.controller.php
│   │   ├───matchs.controller.php
│   │   ├───monsters.controller.php
│   │   ├───types.controller.php
│   │   └───users.controller.php
│   ├───db
│   │   └────db.connector.php
│   ├───models
│   │   ├───__include__all.php
│   │   ├───Hybrid.class.php
│   │   ├───Match.class.php
│   │   ├───Monster.class.php
│   │   ├───Type.class.php
│   │   └───User.class.php
│   └───pollinations
│   │   ├───monster.description.prompt
│   │   ├───monster.image.prompt
│   │   └───Pollinations.class.php
├───vendor
├───.gitignore
├───.htaccess
├───composer.json
├───composer.lock
├───database.sqlite
├───index.php
└───README
```

## API Routes

Voici les principales routes disponibles dans l`API:

### Authentification
- `POST /auth/login` - Connexion utilisateur
- `POST /auth/register` - Inscription utilisateur
- `POST /auth/logout` - Déconnexion utilisateur

## Afficher User
- `GET /users/{uuid}` Afficher les stats de l`utilisateur
- `GET /users/monstres/{uuid}` Afficher tout les monstres de l`utilisateur


### création de monstre
- `POST /monstres/create` - Connexion utilisateur

### création d`hybride
- `POST /hybrids/create` - Création d`un monstre hybride à partir de deux monstres existants

### Création de match 
- `POST /match` - Lancement d`un match entre deux monstres et afficher le gagant


## Documentation des fonctions principales
Voici une documentation des fonctions principales de chaque contrôleur de l`API :

### Auth Controller
- `login($email, $password)` : Permet à un utilisateur de se connecter en utilisant son email et son mot de passe. Retourne un token d`authentification si la connexion est réussie.
- `register($username, $email, $password)` : Permet à un nouvel utilisateur de s'inscrire en fournissant un nom d'utilisateur, un email et un mot de passe. Retourne un code 201 si l`inscription est réussie.
- `logout($token)` : Permet à un utilisateur de se déconnecter en invalidant son token d`authentification. Retourne un code 200 si la déconnexion est réussie.

### Hybrids Controller
- `create($userId, $monstre1, $monstre2)` : Permet de créer un monstre hybride en combinant deux monstres existants identifiés par leurs UUIDs. Retourne un code 201 si le monstre hybride est créé avec succès.

### Matchs Controller
- `match($monstre1, $monstre2)` : Permet de faire combattre deux monstres identifiés par leurs UUIDs. Retourne un code 200 si le combat est réalisé avec succès.

### Monsters Controller
- `generate_image($heads, $types)` : Génère l'image d'un monstre en fonction du nombre de têtes et des types spécifiés. Retourne un code 200 si l`image est générée avec succès.
- `generate_monster_info($name, $heads, $types)` : Génère une description modifiée pour un monstre en fonction de son nom, du nombre de têtes et des types. Retourne un code 200 si la description est générée avec succès.

### Types Controller
- `createType($name)` : Crée un nouveau type de monstre si celui-ci n`existe pas déjà. Retourne un code 201 si le type est créé avec succès.

### Users Controller
- `getUser($id)` : Récupère les informations d'un utilisateur par son UUID, en excluant le mot de passe. Retourne un tableau des informations de l`utilisateur.
- `getMonsterByUser($id)` : Récupère tous les monstres associés à un utilisateur par son UUID. Retourne un tableau des monstres de l`utilisateur.

## Réponses API 
L`API utilise des codes de statut HTTP pour indiquer le résultat des opérations. Voici les principaux codes utilisés :
- `200 OK` : La requête a été traitée avec succès.
- `201 Created` : Une ressource a été créée avec succès.
- `400 Bad Request` : La requête est invalide ou mal formée.
- `401 Unauthorized` : L`authentification est requise ou a échoué.
- `404 Not Found` : La ressource demandée n`a pas été trouvée

## Documentation technique 
Vous allez pouvoir retrouvez un fichier "documentation_technique.pdf" dans le repository qui vous expliquera en détail le fonctionnement de l'API, les choix techniques effectués, ainsi que des exemples d'utilisation pour chaque route disponible. N`hésitez pas à le consulter pour une compréhension approfondie du projet.

## Documentation utilisateurs
Pour les utilisateurs finaux, une documentation utilisateur est également disponible dans le fichier "documentation_utilisateur.pdf". Ce document fournit des instructions claires sur la manière d'utiliser l'API, y compris des exemples de requêtes et de réponses, ainsi que des conseils pour intégrer l`API dans vos applications.
