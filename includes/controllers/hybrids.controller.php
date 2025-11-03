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
     * @param int $heads
     * @param string $types
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

        $pollinations_api_url = "https://image.pollinations.ai/prompt/{$prompt}?width=1042&height=1042&model=gptimage?token=EwVAHta0RAXgtuA2";


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
     * @param string $name
     * @param int $heads
     * @param string $types
     * @return bool|string
     */
    public function generate_monster_hybrid_info(string $name, int $heads, string $types)
    {
        $type_names_final = [];
        foreach(explode('-', $types) as $type_name){
            $smt = $this->pdo->prepare("SELECT * FROM type WHERE uuid = ? limit 1");
            $smt->execute([trim($type_name)]);
            $smt_result = $smt->fetch(PDO::FETCH_ASSOC);
            if($smt_result){
                $type_names_final[] = $smt_result['name'];
            }
        }

        $pollinations = new Pollinations_class($name, $heads, implode('-', $type_names_final));
        $prompt = urlencode($pollinations->getTextePrompt_hybrid($pollinations->getName(), $pollinations->getHeads(), $pollinations->getTypes()));

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
        $hybridName = "Hybride de " . $monsterData1['name'] . " et " . $monsterData2['name'];
        $hybridtypes = $monsterData1['type'] . ',' . $monsterData2['type'];
        $hybriddef_score = ($monsterData1['defense_score'] + $monsterData2['defense_score']) / 2;
        $hybridatt_score = ($monsterData1['attaque_score'] + $monsterData2['attaque_score']) / 2;
        $hybridhealth_score = ($monsterData1['health_score'] + $monsterData2['health_score']) / 2;
        $hybridheads = $monsterData1['heads'] + $monsterData2['heads'];
        $hybridimage = $this->generate_hybrid_image($hybridheads, $hybridtypes, $monstre_1, $monstre_2);
        $hybriddescription = $this->generate_monster_hybrid_info($hybridName, $hybridheads, $hybridtypes);
        $hybrid = new Hybrid_class($hybridName, $hybriddescription, array_merge(explode(',', $monsterData1['type']), explode(',', $monsterData2['type'])) , $hybridimage, $hybridhealth_score, $hybriddef_score, $hybridatt_score, $hybridheads, $created_by , $monstre_1, $monstre_2);
        $hybrid->setName($hybridName)->setDescription($hybriddescription)->setType(array_merge(explode(',', $monsterData1['type']), explode(',', $monsterData2['type'])))->setHeads($hybridheads)->setIsHybride(true);
        if (!$this->pdo) {
            throw new Exception("Connexion à la base de données échouée.");
        }
        $stmt = $this->pdo->prepare("INSERT INTO monstres (uuid, name, description, type, image, health_score, defense_score, attaque_score, heads, created_by, is_hybride) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$hybrid->getId(), $hybrid->getName(), $hybrid->getDescription(), implode(',', $hybrid->getType()), $hybrid->getImage(), $hybrid->getHeal_score(), $hybrid->getDefense_score(), $hybrid->getAttaque_score(), $hybrid->getHeads(), $hybrid->getCreated_by(), true]);
        http_response_code(201);
        return json_encode(["message" => "Hybride créé avec succès"]);
    }
}