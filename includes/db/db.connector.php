<?php 

class Db_connector
{
   private $pdo;

   public function __construct()
   {
    $this->pdo = $this->createPDO();
   }

   private function createPDO()
   {
    try{
        $pdo = new PDO('sqlite:' . __DIR__ . '/../../database.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e){
        die ("Erreur de connexion :" . $e->getMessage());
    }
   }

   public function getPDO()
   {
    return $this->pdo;
   }
}