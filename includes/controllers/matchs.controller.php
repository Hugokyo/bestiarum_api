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
    /**
     * creation de la function match qui prends en params les uuid des deux monstre au quelle on veux les faire combatres
     * @param string $monstre1
     * @param string $monstre2
     * @return bool|string
     */
    public function match(string $monstre1, string $monstre2){
        $stmt = $this->pdo->prepare("SELECT * FROM monstres WHERE uuid = ? OR uuid = ? limit 2");
        $stmt->execute([$monstre1, $monstre2]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) != 2) {
            http_response_code(404);
            echo json_encode(["message" => "404 - Un des monstres n'existe pas"]);
            exit;
        }
        $monsterData1 = json_encode($result[0]);
        $monsterData2 = json_encode($result[1]);
        $prompt = "Tu es un moteur de simulation de combat entre deux créatures mystiques. Je te donne deux tableaux avec leurs statistiques d’attaque, de défense et de soins. Analyse leurs forces et faiblesses pour déterminer le gagnant de manière réaliste (pas aléatoire pur). Génère des logs immersifs (4 à 5 lignes) décrivant le combat étape par étape, avec un ton mythologique. Ne renvoie aucun texte explicatif, aucune introduction, aucun markdown, aucune justification, aucune phrase hors JSON. Je veux uniquement un tableau JSON au format suivant : ['winner':'uuid_du_gagant','logs':['log1','log2','log3','log4']] Voici les données : monstre_1 : {$monsterData1} monstre_2 : {$monsterData2}";
        $pollinations_api_url = "https://text.pollinations.ai/{$prompt}";

        $response = @file_get_contents($pollinations_api_url);
        $data = json_decode($response, true);
        $match = new Match_class(json_encode($data['winner']), $monstre1, $monstre2);
        $stmt = $this->pdo->prepare("INSERT INTO combat (uuid, result, creature_1, creature_2) VALUES (?, ?, ?, ?)");
        $stmt->execute([$match->getUuid(), json_decode($match->getResult()), $match->getMonstre1(), $match->getMonstre2()]);
        http_response_code(201); 
        return json_encode([
            "message" => "Combat réaliser avec succès",
            "winner" => json_decode($match->getResult())
        ]);
    }

}