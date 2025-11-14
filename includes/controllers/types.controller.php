<?php 

require_once __DIR__ . '/../models/Type.class.php';
require_once __DIR__ . '/../db/db.connector.php';

class Types_controller
{
    private $pdo;

    public function __construct(){
        $dbConnector = new Db_connector();
        $this->pdo = $dbConnector->getPDO();
    }
    /**
     * fonction qui permet de créer un type pour la créature, il regarde si le type existe, s'il existe on récupère son uuid sinon on le crée
     * return : 201 - Type créé avec succès
     * utilisation : createType($name)
     * @param string $name
     * @throws \Exception
     */
    public function createType(string $name){
        $type = new Type($name);
        $type->setId();
        $type->setName($name);
    
        if (!$this->pdo) {
            throw new Exception("Database connection failed.");
        }

        $requet = $this->pdo->prepare("SELECT * FROM type WHERE name = ? limit 1");
        $requet->execute([$type->getName()]);
        $result = $requet->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['uuid'];
        }
        if (!$result) {
            if(empty($name)){
                http_response_code(400);
                echo json_encode(["message" => "400 - Le nom du type ne peut pas être vide"]);
                exit;
            }
            if(strpos($name, '-') !== false){
                $hybride_types = explode('-', $name);
                foreach($hybride_types as $hybride_type_name){
                    $hybride_type = new Type($hybride_type_name);
                    $hybride_type->setId();
                    $hybride_type->setName($hybride_type_name);
                    $requet = $this->pdo->prepare("SELECT * FROM type WHERE name = ? limit 1");
                    $requet->execute([$hybride_type->getName()]);
                    $result = $requet->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        $type_ids[] = $result['uuid'];
                    } else {
                        $stmt = $this->pdo->prepare("INSERT INTO type (uuid, name) VALUES (?, ?)");
                        $stmt->execute([$hybride_type->getId() ,$hybride_type->getName()]);
                        $type_ids[] = $hybride_type->getId();
                    }                    
                }
                return $type_ids;
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO type (uuid, name) VALUES (?, ?)");
                $stmt->execute([$type->getId() ,$type->getName()]);
                return $type->getId();
            }
        }

    }

}