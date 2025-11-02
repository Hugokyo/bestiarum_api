<?php 

require_once __DIR__ . '/../models/Match.class.php';
require_once __DIR__ . '/../db/db.connector.php';

class Matchs_controller
{
    private $pdo;

    public function __construct(){
        $dbConnector = new Db_connector();
        $this->pdo = $dbConnector->getPDO();
    }

    public function match(array $monstre1, array $monstre2){
        $monstre1_str = json_encode($monstre1);
        $monstre2_str = json_encode($monstre2);
        $prompt = "Tu es un moteur de simulation de combat entre deux créatures mystiques. Je te donne deux tableaux avec leurs statistiques d’attaque, de défense et de soins. Analyse leurs forces et faiblesses pour déterminer le gagnant de manière réaliste (pas aléatoire pur). Génère des logs immersifs (4 à 5 lignes) décrivant le combat étape par étape, avec un ton mythologique. Ne renvoie aucun texte explicatif, aucune introduction, aucun markdown, aucune justification, aucune phrase hors JSON. Je veux uniquement un tableau JSON au format suivant : [{'winner':'nom_du_gagnant','logs':['log1','log2','log3','log4']}] Voici les données : monstre_1 : {$monstre1_str} monstre_2 : {$monstre2_str}";
        $pollinations_api_url = "https://text.pollinations.ai/{$prompt}";

        $response = @file_get_contents($pollinations_api_url);
        echo $response;
    }

}