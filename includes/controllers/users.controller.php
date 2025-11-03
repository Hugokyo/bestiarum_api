<?php 

require_once __DIR__ . '/../models/User.class.php';
require_once __DIR__ . '/../db/db.connector.php';

class Users_controller
{
    private $pdo;

    public function __construct(){
        $dbConnector = new Db_connector();
        $this->pdo = $dbConnector->getPDO();
    }

    public function getUser(string $id){
        $stmt = $this->pdo->prepare("SELECT email, username, created_at FROM users WHERE uuid = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            http_response_code(200);
            return $user;
        } else {
            http_response_code(404);
            return null;
        }
    }

    public function getMonsterByUser(string $id){
        $stmt = $this->pdo->prepare("SELECT * FROM monstres WHERE created_by = ? LIMIT 1");
        $stmt->execute([$id]);
        $monstresuser = $stmt->fetch(PDO::FETCH_ASSOC);
        return $monstresuser;
        // if ($monstresuser) {
        //     http_response_code(200);
        //     return $monstresuser;
        // } else {
        //     http_response_code(404);
        //     return null;
        // }
    }

}