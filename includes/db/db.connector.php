<?php 

class Db_connector
{
   private $pdo;

   public function __construct()
   {
    $this->pdo = $this->createPDO();
   }

   /**
    * Function de la connexion à la base de données
    * remarque : utilise sqlite pour la simplicité
    * @return PDO
    */
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
   /**
    * Getter pour le pdo
    * @return PDO
    */
   public function getPDO()
   {
    return $this->pdo;
   }
}