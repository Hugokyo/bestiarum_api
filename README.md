# README du projet ARCHI_MONSTRE
bienvenue sur le repository du projet API_PHP_ARCHI_MONSTRE. Sur cette documentation, vous allez retrouver différentes rubriques pour vous expliquer comment installer le projet et faire fonctionner au mieux l`API. Pour cela :

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

## 📡 API Routes

Voici les principales routes disponibles dans l`API:

### Authentification
- `POST /auth/login` - Connexion utilisateur
- `POST /auth/register` - Inscription utilisateur
- `POST /auth/logout` - Déconnexion utilisateur

## Afficher User
- `GET /users/{id}` Afficher les stats de l`utilisateur ainsi que tout c`est créature et match

### création de monstre
- `POST /monstres/create` - Connexion utilisateur

### création d`hybride
- `POST /hybrids/create` - Création d`un monstre hybride à partir de deux monstres existants

### Création de match 
- `POST /match` - Lancement d`un match entre deux monstres et afficher le gagant



