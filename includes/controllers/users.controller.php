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
        
    }

}