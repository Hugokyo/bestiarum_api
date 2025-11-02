<?php 
require_once __DIR__ . '/../models/User.class.php';
require_once __DIR__ . '/../models/Monster.class.php';
require_once __DIR__ . '/../pollinations/Pollinations.class.php';
require_once __DIR__ . '/../db/db.connector.php';

class Monsters_controller
{
    private $pdo;
    public function __construct(){
        $dbConnector = new Db_connector();
        $this->pdo = $dbConnector->getPDO();
    }

    public function generate_image(int $heads, string $types)
    {
        $pollinations = new Pollinations_class('', $heads, $types);
        $prompt = urlencode($pollinations->getImagePrompt($pollinations->getHeads(), $pollinations->getTypes()));

        $pollinations_api_url = "https://image.pollinations.ai/prompt/{$prompt}?width=1042&height=1042&model=gptimage?token=EwVAHta0RAXgtuA2";

        // stocker l'image dans un dossier includes/public/storage
        $response = @file_get_contents($pollinations_api_url);
        if ($response === FALSE) {
            http_response_code(500);
            echo json_encode(["message" => "500 - Erreur lors de la génération de l'image"]);
            exit;
        }
        $target_dir = __DIR__ . "/../public/storage/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        } else {
            $file_name = uniqid('monster_', true) . '.png';
            $file_path = $target_dir . $file_name;
            file_put_contents($file_path, $response);
            $response =  'http://localhost:8000/includes/public/storage/' . $file_name;
        }
        
        return $response;
    }

    public function generate_monster_info(string $name, int $heads, string $types)
    {
        $pollinations = new Pollinations_class($name, $heads, $types);
        $prompt = urlencode($pollinations->getTextePrompt($pollinations->getName(), $pollinations->getHeads(), $pollinations->getTypes()));

        $pollinations_api_url = "https://text.pollinations.ai/{$prompt}";

        $response = @file_get_contents($pollinations_api_url);

        if ($response === FALSE) {
            http_response_code(500);
            echo json_encode(["message" => "500 - Erreur lors de la génération de l'image"]);
            exit;
        }

        return $response; 

    }

    public function create(string $name, string $description, array $type, string $image, int $heal_score, int $defense_score, int $attaque_score, int $heads, string $created_by, bool $is_hybride){
        $monstre = new Monstre_class($name, $description, $type, $image, $heal_score, $defense_score, $attaque_score, $heads, false , $created_by);
        $monstre->setName($name)->setDescription($description)->setType($type)->setHeads($heads)->setIsHybride(false);
        if (!$this->pdo) {
            throw new Exception("Database connection failed.");
        }
        $stmt = $this->pdo->prepare("INSERT INTO monstres (uuid, name, description, type, image, health_score, defense_score, attaque_score, heads, created_by, is_hybride) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $typeString = is_array($monstre->getType()) ? implode(',', $monstre->getType()) : $monstre->getType();
        $stmt->execute([$monstre->getId(), $monstre->getName(), $monstre->getDescription(), $typeString, $monstre->getImage(), $monstre->getHeal_score(), $monstre->getDefense_score(), $monstre->getAttaque_score(), $monstre->getHeads(), $monstre->getCreated_by()]);
        http_response_code(201); 
        return json_encode(["message" => "Monstre créé avec succès"]);
    }

}

