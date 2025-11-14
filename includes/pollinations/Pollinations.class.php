<?php 

class Pollinations_class
{
    private string $prompt;
    private string $name;
    private int $heads;
    private string $types;

    public function __construct(string $name = '', int $heads = 1, string $types = '')
    {
        $this->getImagePrompt($heads, $types);
        $this->getTextePrompt($name, $heads, $types);
        $this->getImage_hybridPrompt($heads, $types, [], []);
        $this->getTextePrompt_hybrid($heads, $types, [], []);
        $this->setHeads($heads);
        $this->setTypes($types);
        $this->setName($name);
    }

    /**
     * Function qui permet de générer le prompt pour les images
     * Utilisations : getImagePrompt(2, 'feu, eau')
     * @param int $heads
     * @param string $types
     * @return string
     */
    public function getImagePrompt(int $heads, string $types) : string
    {
        return $this->prompt = "Creature imaginaire mythologique, type:{$types}, nombres de tete(s) : {$heads} (mon monstre doit absolument avoir se nombre de têtes), apparence puissante et mystique, inspiree des legendes anciennes, style realiste et detaille, textures precises (ecailles, fourrure, cornes), lumiere dramatique, rendu concept art cinematographique. Apparence refletant son type (ex: feu=teintes rouges/orangees, reflets de lave, chaleur). Decor correspondant: feu=volcan, air=tempete, eau= ocean, terre=foret. Ambiance epique, surnaturelle, credible et realiste. Image unique a chaque requete.";
    }
    /**
     * Function qui permet de générer le prompt pour les images d'hybrides
     * Utilisations : getImage_hybridPrompt('2', 'feu, eau', $parent1, $parent2)
     * @param int $heads
     * @param string $types
     * @param array $parent1
     * @param array $parent2
     * @return string
     */
    public function getImage_hybridPrompt(int $heads, string $types, array $parent1, array $parent2) : string
    {
        $parent1Str = json_encode($parent1);
        $parent2Str = json_encode($parent2);
        return $this->prompt = "Hybride mythologique issu de deux parents voici leurs donnée {$parent1Str} et {$parent2Str}, combinant leurs caractéristiques physiques et élémentaires. Type principal : {$types}, {$heads} tête(s). Apparence harmonieuse et puissante, fusion réaliste des traits distinctifs des deux parents (ex : ailes d’un dragon, pelage d’un loup, cornes d’un taureau). Style réaliste et détaillé, textures précises (écailles, fourrure, plumes, cornes). Lumière dramatique, rendu concept art cinématographique. Couleurs et effets reflétant la nature des parents (ex : feu + eau = vapeur et lueurs rouge-bleu). Décor cohérent selon les éléments dominants (feu=volcan, air=tempête, eau=océan, terre=forêt). Ambiance épique, surnaturelle, crédible et réaliste. Image unique à chaque requête.";
    }
    /**
     * Function qui permet de générer le prompt pour le texte de la créature
     * Utilisations : getTextePrompt('Dragon', '2', 'feu')
     * @param string $name
     * @param int $heads
     * @param string $types
     * @return string
     */
    public function getTextePrompt(string $name, int $heads, string $types) : string
    {
        return $this->prompt = "Remplis les données suivantes avec des valeurs inventées pour générer une créature mythologique. Donne uniquement un tableau JSON (aucun texte autour, aucune explication, aucune balise). Les champs health_score, defense_score et attaque_score doivent être des nombres aléatoires compris entre 0 et 100. Voici les variables : nom : {$name}, nombre de tête : {$heads}, type : {$types}. Le champ description doit contenir une courte description de deux lignes maximum. Format attendu : [{'name': '{$name}', 'heads': {$heads}, 'types': '{$types}', 'description': 'Une courte description en deux lignes maximum.','health_score': nombre aléatoire (0–100), 'defense_score': nombre aléatoire (0–100), 'attaque_score': nombre aléatoire (0–100) }]";
    }
    /**
     * Function qui permet de générer le prompt pour le texte de l'hybride
     * Utilisations : getTextePrompt_hybrid(2, 'feu, eau', $parent1, $parent2)
     * @param int $heads
     * @param string $types
     * @param array $parent1
     * @param array $parent2
     * @return string
     */
    public function getTextePrompt_hybrid(int $heads, string $types, array $parent1, array $parent2) : string
    {
        $parent1Str = json_encode($parent1);
        $parent2Str = json_encode($parent2);
        return $this->prompt = "Remplis les données suivantes avec des valeurs inventées pour générer une créature mythologique hybride issue de deux parents dont voici les données {$parent1Str} et {$parent2Str}. Donne uniquement un tableau JSON (aucun texte autour, aucune explication, aucune balise). Les champs health_score, defense_score et attaque_score doivent être des nombres aléatoires compris entre 0 et 100. Voici les variables : nombre de tête : {$heads}, type : {$types}. Le champ description doit contenir une courte description de deux lignes maximum. Format attendu : [{'name': 'Nom de l\'hybride', 'heads': {$heads}, 'types': '{$types}', 'description': 'Une courte description en deux lignes maximum. met le nom des deux parents implicitement (tu fait de façon nature pas juste balancer)','health_score': nombre aléatoire (0–100), 'defense_score': nombre aléatoire (0–100), 'attaque_score': nombre aléatoire (0–100) }]";
    }


    /**
     * getter pour le name
     */ 
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * setter pour le name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getter pour le heads
     */ 
    public function getHeads() : int
    {
        return $this->heads;
    }

    /**
     * setter pour le heads
     *
     * @return  self
     */ 
    public function setHeads(int $heads)
    {
        $this->heads = $heads;

        return $this;
    }

    /**
     * getter pour le types
     */ 
    public function getTypes() : string
    {
        return $this->types;
    }

    /**
     * setter pour le types
     *
     * @return  self
     */ 
    public function setTypes(string $types)
    {
        $this->types = $types;

        return $this;
    }
}