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
        $this->setHeads($heads);
        $this->setTypes($types);
        $this->setName($name);
    }

    /**
     * Get the value of prompt
     */ 
    public function getImagePrompt(int $heads, string $types) : string
    {
        return $this->prompt = "Creature imaginaire mythologique, type:{$types}, {$heads} tete(s), apparence puissante et mystique, inspiree des legendes anciennes, style realiste et detaille, textures precises (ecailles, fourrure, cornes), lumiere dramatique, rendu concept art cinematographique. Apparence refletant son type (ex: feu=teintes rouges/orangees, reflets de lave, chaleur). Decor correspondant: feu=volcan, air=tempete, eau= ocean, terre=foret. Ambiance epique, surnaturelle, credible et realiste. Image unique a chaque requete.";
    }

    public function getImage_hybridPrompt(int $heads, string $types, array $parent1, array $parent2) : string
    {
        $parent1Str = json_encode($parent1);
        $parent2Str = json_encode($parent2);
        return $this->prompt = "Hybride mythologique issu de deux parents voici leurs donnée {$parent1Str} et {$parent2Str}, combinant leurs caractéristiques physiques et élémentaires. Type principal : {$types}, {$heads} tête(s). Apparence harmonieuse et puissante, fusion réaliste des traits distinctifs des deux parents (ex : ailes d’un dragon, pelage d’un loup, cornes d’un taureau). Style réaliste et détaillé, textures précises (écailles, fourrure, plumes, cornes). Lumière dramatique, rendu concept art cinématographique. Couleurs et effets reflétant la nature des parents (ex : feu + eau = vapeur et lueurs rouge-bleu). Décor cohérent selon les éléments dominants (feu=volcan, air=tempête, eau=océan, terre=forêt). Ambiance épique, surnaturelle, crédible et réaliste. Image unique à chaque requête.";
    }

    public function getTextePrompt(string $name, int $heads, string $types) : string
    {
        return $this->prompt = "Remplis les données suivantes avec des valeurs inventées pour générer une créature mythologique. Donne uniquement un tableau JSON (aucun texte autour, aucune explication, aucune balise). Les champs health_score, defense_score et attaque_score doivent être des nombres aléatoires compris entre 0 et 100. Voici les variables : nom : {$name}, nombre de tête : {$heads}, type : {$types}. Le champ description doit contenir une courte description de deux lignes maximum. Format attendu : [{'name': '{$name}', 'heads': {$heads}, 'types': '{$types}', 'description': 'Une courte description en deux lignes maximum.','health_score': nombre aléatoire (0–100), 'defense_score': nombre aléatoire (0–100), 'attaque_score': nombre aléatoire (0–100) }]";
    }

    public function getTextePrompt_hybrid(string $name, int $heads, string $types) : string
    {
        return $this->prompt = "Donne une description pour un monstre hybride nommé {$name}, possédant {$heads} tête(s) et de type(s) {$types}. La description doit être courte (maximum deux lignes), immersive et captivante, en mettant en valeur les caractéristiques uniques du monstre. Intègre dans la description que ce monstre est un hybride issu de la fusion des parents {$name} (parent 1 avant le tirer et parent 2 apres le tirer), où les deux parents sont séparés par un tiret (-). Ne renvoie que la description, sans texte supplémentaire ni introduction.";
    }


    /**
     * Get the value of name
     */ 
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of heads
     */ 
    public function getHeads() : int
    {
        return $this->heads;
    }

    /**
     * Set the value of heads
     *
     * @return  self
     */ 
    public function setHeads(int $heads)
    {
        $this->heads = $heads;

        return $this;
    }

    /**
     * Get the value of types
     */ 
    public function getTypes() : string
    {
        return $this->types;
    }

    /**
     * Set the value of types
     *
     * @return  self
     */ 
    public function setTypes(string $types)
    {
        $this->types = $types;

        return $this;
    }
}