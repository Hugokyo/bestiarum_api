<?php 

require_once __DIR__ . '/../models/User.class.php';
require_once __DIR__ . '/../models/Monster.class.php';
require_once __DIR__ . '/../pollinations/Pollinations.class.php';
require_once __DIR__ . '/../db/db.connector.php';
require_once __DIR__ . '/../models/Hybrid.class.php';

class Hybrids_controller 
{

    private $pdo;
    public function __construct(){
        $dbConnector = new Db_connector();
        $this->pdo = $dbConnector->getPDO();
    }
    /**
     * function permettant de crée la photo pour la créatures hybrid et la stocker dans notre serveur
     * return : 200 - Image générée avec succès
     * utilisation : generate_hybrid_image($heads, $types, $monstre_1, $monstre_2)
     * @param int $heads
     * @param string $types
     * @param string $monstre_1
     * @param string $monstre_2
     * @return bool|string
     */
    public function generate_hybrid_image(int $heads, string $types, string $monstre_1, string $monstre_2)
    {
        /**
         * Récupération des données des deux monstres parents
         * @var mixed
         */
        $stmt = $this->pdo->prepare("SELECT * FROM monstres WHERE uuid = ? OR uuid = ? limit 2");
        $stmt->execute([$monstre_1, $monstre_2]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) != 2) {
            http_response_code(404);
            echo json_encode(["message" => "404 - Un des monstres n'existe pas"]);
            exit;
        }
        $monsterData1 = $result[0];
        $monsterData2 = $result[1];


        $pollinations = new Pollinations_class('', $heads, $types);
        $prompt = urlencode($pollinations->getImage_hybridPrompt($pollinations->getHeads(), $pollinations->getTypes(), $monsterData1, $monsterData2));

        $pollinations_api_url = "https://image.pollinations.ai/prompt/{$prompt}?width=1042&height=1042";


        /**
         * Stockage de l'image dans les fichier serveur
         * @var mixed
         */
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
    /**
     * function qui permet de générer la description modifier pour la créature hybrids
     * return : 200 - Description générée avec succès
     * utilisation : generate_monster_hybrid_info($parent1, $parent2, $heads, $types)
     * @param string $name
     * @param int $heads
     * @param string $types
     * @param string $parent1
     * @param string $parent2
     * @return bool|string
     */
    public function generate_monster_hybrid_info(string $parent1, string $parent2, int $heads, string $types)
    {
        /**
         * Récupération des données des deux monstres parents
         * @var mixed
         */
        $stmt = $this->pdo->prepare("SELECT * FROM monstres WHERE uuid = ? OR uuid = ? limit 2");
        $stmt->execute([$parent1, $parent2]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) != 2) {
            http_response_code(404);
            echo json_encode(["message" => "404 - Un des monstres n'existe pas"]);
            exit;
        }
        $monsterData1 = $result[0];
        $monsterData2 = $result[1];
        $pollinations = new Pollinations_class();
        $prompt = urlencode($pollinations->getTextePrompt_hybrid($heads, $types, $monsterData1, $monsterData2));

        $pollinations_api_url = "https://text.pollinations.ai/{$prompt}";

        $response = @file_get_contents($pollinations_api_url);

        if ($response === FALSE) {
            http_response_code(500);
            echo json_encode(["message" => "500 - Erreur lors de la génération de l'image"]);
            exit;
        }

        return $response; 
    }

    /**
     * fonction qui permet de créer l'hybride de deux créatures existantes, il prend en paramètre l'id des deux parents et récupère toutes les informations sur ces parents, et crée un monstre avec le mélange des informations de ces deux parents
     * return : 201 - Monstre hybride créé avec succès
     * utilisation : create($created_by, $monstre_1, $monstre_
     * @param string $created_by
     * @param string $monstre_1
     * @param string $monstre_2
     * @throws \Exception
     * @return bool|string
     */
    public function create(string $created_by, string $monstre_1, string $monstre_2){
        $stmt = $this->pdo->prepare("SELECT * FROM monstres WHERE uuid = ? OR uuid = ? limit 2");
        $stmt->execute([$monstre_1, $monstre_2]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) != 2) {
            http_response_code(404);
            echo json_encode(["message" => "404 - Un des monstres n'existe pas"]);
            exit;
        }
        $monsterData1 = $result[0];
        $monsterData2 = $result[1];
        $hybridheads = $monsterData1['heads'] + $monsterData2['heads'];
        $pollinations = new Pollinations_class('', ($monsterData1['heads'] + $monsterData2['heads']) / 2, implode('-', [implode(',', is_array($monsterData1['type']) ? $monsterData1['type'] : explode(',', $monsterData1['type'])), implode(',', is_array($monsterData2['type']) ? $monsterData2['type'] : explode(',', $monsterData2['type']))]));
        $prompt = urlencode($pollinations->getTextePrompt_hybrid($pollinations->getHeads(), $pollinations->getTypes(), $monsterData1, $monsterData2));
        $pollinations_api_url = "https://text.pollinations.ai/{$prompt}";
        $response = @file_get_contents($pollinations_api_url);
        if ($response === FALSE) {
            http_response_code(500);
            echo json_encode(["message" => "500 - Erreur lors de la génération de la description"]);
            exit;
        }
        $hybridData = json_decode($response, true)[0];
        $image_url = $this->generate_hybrid_image($hybridData['heads'], $hybridData['types'], $monstre_1, $monstre_2);
        $hybride = new Hybrid_class($hybridData['name'], $hybridData['description'], array_merge(explode(',', $monsterData1['type']), explode(',', $monsterData2['type'])), $image_url, $hybridData['health_score'], $hybridData['defense_score'], $hybridData['attaque_score'], $hybridData['heads'], $created_by, $monstre_1, $monstre_2);
        $hybride->setName($hybridData['name'])->setDescription($hybridData['description'])->setType(array_merge(explode(',', $monsterData1['type']), explode(',', $monsterData2['type'])))->setHeads($hybridData['heads'])->setIsHybride(true)->setCreated_by($created_by);
        if (!$this->pdo) {
            throw new Exception("Database connection failed.");
        }
        /**
         * Stockage de l'hybride dans la base de données
         * @var mixed
         */
        $stmt = $this->pdo->prepare("INSERT INTO monstres (uuid, name, description, type, image, health_score, defense_score, attaque_score, heads, created_by, is_hybride) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $typeString = is_array($hybride->getType()) ? implode(',', $hybride->getType()) : $hybride->getType();
        $stmt->execute([$hybride->getId(), $hybride->getName(), $hybride->getDescription(), $typeString, $hybride->getImage(), $hybride->getHeal_score(), $hybride->getDefense_score(), $hybride->getAttaque_score(), $hybridheads, $hybride->getCreated_by(), true]);
        http_response_code(201);
        return json_encode(["message" => "Monstre hybride créé avec succès"]);
    }
}