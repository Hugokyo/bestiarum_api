<?php 
require_once __DIR__ . '/../models/User.class.php';
require 'vendor/autoload.php';
require_once __DIR__ . '/../db/db.connector.php';

use Firebase\JWT\JWT;
 

class Auth_controller 
{
    private $pdo;

    public function __construct(){
        $dbConnector = new Db_connector();
        $this->pdo = $dbConnector->getPDO();
    }
    /**
     * Function pour l'endpoint register
     * Return : 201 - Utilisateur créé avec succès
     * Utilisations : register($username, $email, $password)
     * @param string $username
     * @param string $email
     * @param string $password
     * @return void
     */
    public function register(string $username, string $email, string $password)
    {
        $user = new User($username, $email, $password);
        $user->setId();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setUsername($username);

        if (!$this->pdo) {
            throw new Exception("Database connection failed.");
        }
        $stmt = $this->pdo->prepare("INSERT INTO users (uuid, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user->getId() ,$user->getUsername(), $user->getEmail(), $user->getPassword()]);
        
    }

    /**
     * Function pour l'endpoint login
     * Return : 200 - Connexion réussie
     * Utilisations : login($email, $password)
     * @param string $email
     * @param string $password
     * @return void
     */
    public function login(string $email, string $password)
    {
        $user = new User($email, $password);
        $user->setEmail($email);
        $user->setPassword($password);
        if (!$this->pdo) {
            throw new Exception("Database connection failed.");
        }
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? limit 1");
        $stmt->execute([$user->getEmail()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && password_verify($password, $result["password"])) {
            $key = '1a3LM3W966D6QTJ5BJb9opunkUcw_d09NCOIJb9QZTsrneqOICoMoeYUDcd_NfaQyR787PAH98Vhue5g938jdkiyIZyJICytKlbjNBtebaHljIR6-zf3A2h3uy6pCtUFl1UhXWnV6madujY4_3SyUViRwBUOP-UudUL4wnJnKYUGDKsiZePPzBGrF4_gxJMRwF9lIWyUCHSh-PRGfvT7s1mu4-5ByYlFvGDQraP4ZiG5bC1TAKO_CnPyd1hrpdzBzNW4SfjqGKmz7IvLAHmRD-2AMQHpTU-hN2vwoA-iQxwQhfnqjM0nnwtZ0urE6HjKl6GWQW-KLnhtfw5n_84IRQ';
            $token = JWT::encode(
					array(
						'iat'		=>	time(),
						'nbf'		=>	time(),
						'exp'		=>	time() + 3600,
						'data'	=> array(
                            'uuid' => $result['uuid'],
                            'username'	=>	$result['username'],
							'email'	=>	$result['email'],
                            'created_at' => $result['created_at']
                        )
                    ),
                    $key,
                        'HS256'
                    );
            header("Set-Cookie: token={$token}; Secure; Path=/; SameSite=None; Partitioned;");
            http_response_code(200);
            echo json_encode([
                "message" => "200 - Connexion réussie",
                "token" => $token,
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "401 - Email ou mot de passe incorrect"]);
        }
    }
    /**
     * la function pour permettre a l'utilisateur de se deconnecter, il supprime le cookie qui est stocker dans le header
     * Return : 200 - Utilisateur déconnecter
     * utilisation : logout()
     * @return void
     */
    public function logout()
    {
        if(isset($_COOKIE['token'])){
            setcookie('token', '', time() - 3600, '/');
            http_response_code(200);
            echo json_encode([
                "message" => "200 - Utilisateur déconnecter",
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "401 - Veillez vous connecter"]);
        }
    }
    /**
     * permet de ne pas répéter le module header dans chaque requettes
     * @param string $method
     * @param mixed $data
     * @return bool|string
     */
    public function header(string $method, bool $authorisation = false, string ...$requiredFields)
    {
        if ($_SERVER['REQUEST_METHOD'] !==  $method) {
            http_response_code(405);
            $json = json_encode(["message" => "405 - Méthode non autorisée"]);
            return $json;
        }
        if ($authorisation) {
            if (!isset($_SERVER['HTTP_AUTHORIZATION']) ||strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ') !== 0) {
                http_response_code(401);
                return json_encode(["message" => "401 - Authentification Bearer requise"]);
            }
        }
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true) ?? [];
        foreach ($requiredFields as $field) {
            if (!isset($input[$field]) || $input[$field] === '') {
                http_response_code(401);
                return json_encode([
                    "message" => "401 - Données incomplètes pour cette requête",
                    "missing" => $field
                ]);
            }
        }
        return true;
    }
    /**
     * Permet de récupérer l'id de l'utilisateur avec le token
     * @param string $token
     */
    public function getIdWithToken(string $token)
    {
        $key = '1a3LM3W966D6QTJ5BJb9opunkUcw_d09NCOIJb9QZTsrneqOICoMoeYUDcd_NfaQyR787PAH98Vhue5g938jdkiyIZyJICytKlbjNBtebaHljIR6-zf3A2h3uy6pCtUFl1UhXWnV6madujY4_3SyUViRwBUOP-UudUL4wnJnKYUGDKsiZePPzBGrF4_gxJMRwF9lIWyUCHSh-PRGfvT7s1mu4-5ByYlFvGDQraP4ZiG5bC1TAKO_CnPyd1hrpdzBzNW4SfjqGKmz7IvLAHmRD-2AMQHpTU-hN2vwoA-iQxwQhfnqjM0nnwtZ0urE6HjKl6GWQW-KLnhtfw5n_84IRQ';
        try {
            $decoded = JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));
            return $decoded->data->uuid;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["message" => "401 - Token invalide"]);
            exit;
        }
    }

}