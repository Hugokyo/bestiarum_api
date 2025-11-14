<?php
require_once __DIR__ . '/includes/db/db.connector.php';
require_once __DIR__ . '/includes/controllers/auth.controller.php';
require_once __DIR__ . '/includes/controllers/users.controller.php';
require_once __DIR__ . '/includes/controllers/types.controller.php';
require_once __DIR__ . '/includes/controllers/monsters.controller.php';
require_once __DIR__ . '/includes/controllers/hybrids.controller.php';
require_once __DIR__ . '/includes/controllers/matchs.controller.php';

$db = new Db_connector();
$pdo = $db->getPDO();


$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$path = str_replace(dirname($scriptName), '', $requestUri);
$route = trim($path, '/');

if(!empty($path)){
    switch ($route) {
        /**
         * Route pour l'enregistrement d'un utilisateur
         * PARAMS: username, email, password
         * RETURN : 201 - Utilisateur créé avec succès
         */
        case 'auth/register':         
            $usersController = new Auth_controller();
            $auth = new Auth_controller();
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            $headerCheck = $auth->header('POST', false, 'username', 'email', 'password');
            if ($headerCheck !== true){
                echo $headerCheck;
                break;
            }
            $usersController->register($data['username'], $data['email'], $data['password']);

            http_response_code(201);
            echo json_encode(["message" => "201 - Utilisateur créé avec succès"]);
            break;

        /**
         * Route pour la connexion d'un utilisateur
         * PARAMS: email, password
         * RETURN : 200 - Connexion réussie
         */
        case 'auth/login':
            $auth = new Auth_controller();
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            $headerCheck = $auth->header('POST', false, 'email', 'password');
            if ($headerCheck !== true){
                echo $headerCheck;
                break;
            }
            $usersController = new Auth_controller();
            $usersController->login($data['email'], $data['password']);
            break;
        /**
         * Route pour la déconnexion d'un utilisateur
         * PARAMS: token dans le header Authorization
         * RETURN : 200 - Utilisateur déconnecter
         */
        case 'auth/logout':
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                http_response_code(400);
                echo json_encode(["message" => "400 - bad resquest"]);
                break;
            }
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            $usersController = new Auth_controller();
            echo $usersController->logout();
            break;
        /**
         * Route pour la création d'un type de monstre
         * PARAMS: name
         * RETURN : 201 - Type créé avec succès
         */
        case 'type/create':
            $auth = new Auth_controller();
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            $headerCheck = $auth->header('POST', true, 'name');
            if ($headerCheck !== true){
                echo $headerCheck;
                break;
            }
            $typeController = new Types_controller();
            echo json_encode($typeController->createType($data['name']));
            break;
        /**
         * Route pour la création d'une créature
         * PARAMS: name, heads, types
         * RETURN : 201 - Créature créée avec succès
         */
        case 'montres/create':
            $auth = new Auth_controller();
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            $headerCheck = $auth->header('POST', true, 'name', 'heads', 'types');
            if ($headerCheck !== true){
                echo $headerCheck;
                break;
            }
            $pollinations = new Monsters_controller();
            $responseImage = $pollinations->generate_image($data['heads'], $data['types']);
            $responseTextJson = $pollinations->generate_monster_info($data['name'], $data['heads'], $data['types']);
            $responseText = json_decode($responseTextJson, true);

            $monstre = new Monsters_controller();
            $typeController = new Types_controller();
            $typedata = $typeController->createType($data['types']);
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
            $token = str_replace('Bearer ', '', $authHeader);
            $authController = new Auth_controller();
            $userId = $authController->getIdWithToken($token);
            echo $monstre->create($data['name'], $responseText[0]['description'], $typedata, $responseImage, $responseText[0]['health_score'], $responseText[0]['defense_score'], $responseText[0]['attaque_score'], $data['heads'], $userId, false
            );
            break;
        /**
         * Route pour la création d'un hybride de créatures
         * PARAMS: monstre_1, monstre_2
         * RETURN : 201 - Hybride créé avec succès
         */
        case 'hybrids/create':
            $auth = new Auth_controller();
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            $headerCheck = $auth->header('POST', true, 'monstre_1', 'monstre_2');
            if ($headerCheck !== true){
                echo $headerCheck;
                break;
            }
            $hybrid = new Hybrids_controller();
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
            $token = str_replace('Bearer ', '', $authHeader);
            $authController = new Auth_controller();
            $userId = $authController->getIdWithToken($token);
            echo $hybrid->create($userId, $data['monstre_1'], $data['monstre_2']);
            break;
        /**
         * Route pour effectuer un match entre deux créatures
         * PARAMS: monstre_1, monstre_2
         * RETURN : 200 - Résultat du match
         */
        case 'match':
            $auth = new Auth_controller();
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            $headerCheck = $auth->header('POST', true, 'monstre_1', 'monstre_2');
            if ($headerCheck !== true){
                echo $headerCheck;
                break;
            }
            $match = new Matchs_controller();
            echo $match->match($data['monstre_1'], $data['monstre_2']);
            break;
        /**
         * Route pour récupérer les informations d'un utilisateur
         * PARAMS: uuid
         * RETURN : array des informations de l'utilisateur
         */
        case 'users':
            $auth = new Auth_controller();
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            $headerCheck = $auth->header('GET', true, 'uuid');
            if ($headerCheck !== true){
                echo $headerCheck;
                break;
            }
            $user = new Users_controller();
            echo json_encode($user->getUser($data['uuid']));
            break;
        /**
         * Route pour récupérer les créatures d'un utilisateur
         * PARAMS: uuid
         * RETURN : array des créatures de l'utilisateur
         */
        case 'users/monstres': 
            $auth = new Auth_controller();
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            $headerCheck = $auth->header('GET', true, 'uuid');
            if ($headerCheck !== true){
                echo $headerCheck;
                break;
            }
            $user = new Users_controller();
            echo json_encode($user->getMonsterByUser($data['uuid']));
            break;
        /**
         * Route par défaut si la route n'existe pas
         * RETURN : 400 - Route invalide
         */
        default:
            http_response_code(400); 
            
            echo json_encode(["message" => "400 - Route invalide"]);

    }
} else {
    http_response_code(401);
    echo json_encode(["message" => "400 - Route invalide"]);
}
