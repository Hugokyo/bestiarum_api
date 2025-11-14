

**Documentation utilisateur bestiarum api**  
“Cette documentation utilisateur explique comment utiliser l'application : création de compte, gestion des monstres, navigation, et fonctionnalités principales.”  
Dernière version le 14/11/2025 à 21h  
Par BOHARD hugo

## **1\. Accéder à l’application**

Actuellement, pour accéder à l'application, la version front-end de notre application n'est pas encore disponible, dans quelques mois une version utilisable (interface utilisateur) sera sans doute déployée sur l'URL suivante 

❌ bestiarum.bohard-hugo.com (pas disponible)

Actuellement, vous pourrez retrouver le code source de notre API sur l'URL GitHub suivante

[https://github.com/Hugokyo/bestiarum\_api](https://github.com/Hugokyo/bestiarum_api)

## **2.2. Accès à l’API**

L’API est accessible localement via l’URL suivante :  
‘http://localhost:8000’

Pour cela, lancer le serveur PHP depuis le fichier code avec cette commande   
‘php \-S localhost:8000’

## **2.3. Outils nécessaires**

Pour utiliser l’API sans interface graphique, les utilisateurs doivent disposer d’un outil capable d’envoyer des requêtes HTTP, pour cela vous pouviez utiliser : 

* **Postman** : interface graphique pour tester les endpoints.

* **Bruno** : alternative légère pour tester les requêtes HTTP.

* **cURL** : outil en ligne de commande permettant d’effectuer des appels API.

* Tout client HTTP (Thunder Client, navigateur avec extensions, etc.).

## **2.4. Procédure générale d’utilisation**

L’utilisation de l’API suit le cycle suivant :

1. ***Créer un compte** via `/auth/register`*  
2. ***Se connecter** via `/auth/login` afin d’obtenir un token d’authentification*  
3. ***Utiliser les fonctionnalités principales** selon ses besoins : Création d’une créatures, match, hybrids, récupérer les informations utilisateurs…*  
4. *Chaque requête protégée doit contenir le **token d’authentification** dans les headers*

## **2.5. Exemples d’utilisation (Postman ou cURL)**

**2.5.1 Authentification**

| Méthode | Endpoint | Description |
| :---- | :---- | :---- |
| **POST** | **`/auth/login`** | **Permet la connexion d’un utilisateur** |
| **POST** | **`/auth/register`** | **Crée un nouvel utilisateur** |
| **POST** | **`/auth/logout`** | **Déconnecte l’utilisateur en invalidant son token** |

**2.5..2 Utilisateurs**

| Méthode | Endpoint | Description |
| :---- | :---- | :---- |
| **GET** | **`/users/{uuid}`** | **Récupère les informations d’un utilisateur identifié par son UUID** |
| **GET** | **`/users/monstres/{uuid}`** | **Récupère l’ensemble des monstres associés à un utilisateur** |

**2.5.2 Création de monstres**

| Méthode | Endpoint | Description |
| :---- | :---- | :---- |
| **POST** | **`/monstres/create`** | **Crée un nouveau monstre** |

### 

### **2.5..4. Création d’hybrides**

| Méthode | Endpoint | Description |
| :---- | :---- | :---- |
| **POST** | **`/hybrids/create`** | **Génère un monstre hybride à partir de deux monstres existants** |

### **2.5.5. Matchs entre monstres**

| Méthode | Endpoint | Description |
| :---- | :---- | :---- |
| **POST** | **`/match`** | **Lance un match entre deux monstres et renvoie le gagnant** |

### **2.5.6 Codes de statut utilisés par l’API**

| Code | Signification |
| :---- | :---- |
| **200** | **Requête traitée avec succès** |
| **201** | **Ressource créée avec succès** |
| **400** | **Requête invalide ou mal formée** |
| **401** | **Authentification requise ou incorrecte** |
| **404** | **Ressource demandée introuvable** |

## **2.7. Limitations actuelles**

Actuellement, pour son fonctionnement, l'API utilise une API externe pour générer des images et du contenu avec une IA, cette IA s'appelle Pollinations. Pour utiliser cette IA, une limitation dans leurs requêtes et initier de 15 seconds par générations merci d'étre patients entre chaque génération. 