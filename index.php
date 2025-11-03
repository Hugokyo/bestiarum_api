<?php
require_once __DIR__ . '/includes/db/db.connector.php';
require_once __DIR__ . '/includes/controllers/auth.controller.php';
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
        case 'auth/register':
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);

            if (!isset($data['username'], $data['email'], $data['password'])) {
                http_response_code(401);
                echo json_encode(["message" => "401 - Données incomplètes pour cette requette"]);
            }
            $usersController = new Auth_controller();
            $usersController->register($data['username'], $data['email'], $data['password']);

            http_response_code(201);
            echo json_encode(["message" => "201 - Utilisateur créé avec succès"]);
            break;

        case 'auth/login':
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            if (!isset($data['email'], $data['password'])) {
                echo json_encode(["message" => "401 - Données incomplètes pour cet requette"]);
            }
            $usersController = new Auth_controller();
            $usersController->login($data['email'], $data['password']);
            break;
        case 'auth/logout':
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                http_response_code(400);
                echo json_encode(["message" => "400 - bad resquest"]);
                break;
            }
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            $usersController = new Auth_controller();
            $usersController->logout();
            http_response_code(201);
            echo json_encode(["message" => "201 - Utilisateur déconnecter"]);
            break;

        case 'type/create':
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Allow-Methods: POST");
            header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            if (!isset($data['name'])) {
                http_response_code(401);
                echo json_encode(["message" => "401 - Données incomplètes pour cet requette"]);
            }
            $_SERVER['REQUEST_METHOD'] === 'POST';
            if (!isset($_SERVER['HTTP_AUTHORIZATION']) || strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ') !== 0) {
                http_response_code(401);
                echo json_encode(["message" => "401 - Authentification Bearer requise"]);
                exit;
            }
            $typeController = new Types_controller();
            echo json_encode($typeController->createType($data['name']));
            
            
            // echo json_encode(["message" => "201 - Type " . $data['name'] . " crée avec succès"]);
            break;

        case 'montres/create':
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Allow-Methods: POST");
            header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            if (!isset($data['name'], $data['heads'], $data['types'])) {
                http_response_code(401);
                echo json_encode(["message" => "401 - Données incomplètes pour cet requette"]);
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(["message" => "405 - Méthode non autorisée"]);
                exit;
            }
            if (!isset($_SERVER['HTTP_AUTHORIZATION']) || strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ') !== 0) {
                http_response_code(401);
                echo json_encode(["message" => "401 - Authentification Bearer requise"]);
                exit;
            }

            $pollinations = new Monsters_controller();
            $responseImage = $pollinations->generate_image($data['heads'], $data['types']);
            $responseTextJson = $pollinations->generate_monster_info($data['name'], $data['heads'], $data['types']);
            $responseText = json_decode($responseTextJson, true);

            $monstre = new Monsters_controller();
            $user = new User();
            $typeController = new Types_controller();
            $typedata = $typeController->createType($data['types']);
            echo $monstre->create($data['name'], $responseText[0]['description'], $typedata, $responseImage, $responseText[0]['health_score'], $responseText[0]['defense_score'], $responseText[0]['attaque_score'], $data['heads'], $user->getId(), false
            );
            break;
        case 'hybrids/create':
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Allow-Methods: POST");
            header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
             if (!isset($data['monstre_1'], $data['monstre_2'])) {
                http_response_code(401);
                echo json_encode(["message" => "401 - Données incomplètes pour cet requette"]);
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(["message" => "405 - Méthode non autorisée"]);
                exit;
            }
            if (!isset($_SERVER['HTTP_AUTHORIZATION']) || strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ') !== 0) {
                http_response_code(401);
                echo json_encode(["message" => "401 - Authentification Bearer requise"]);
                exit;
            }
            $hybrid = new Hybrids_controller();
            $user = new User();
            echo $hybrid->create($user->getId(), $data['monstre_1'], $data['monstre_2']);
            break;
        case 'match':
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Allow-Methods: POST");
            header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
             if (!isset($data['monstre_1'], $data['monstre_2'])) {
                http_response_code(401);
                echo json_encode(["message" => "401 - Données incomplètes pour cet requette"]);
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(["message" => "405 - Méthode non autorisée"]);
                exit;
            }
            if (!isset($_SERVER['HTTP_AUTHORIZATION']) || strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ') !== 0) {
                http_response_code(401);
                echo json_encode(["message" => "401 - Authentification Bearer requise"]);
                exit;
            }
            $match = new Matchs_controller();
            echo $match->match($data['monstre_1'], $data['monstre_2']);
            break;
        default:
            http_response_code(400); 
            
            echo json_encode(["message" => "400 - Route invalide"]);

    }
} else {
    http_response_code(401);
    echo json_encode(["message" => "400 - Route invalide"]);
}
