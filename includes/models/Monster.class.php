<?php 

class Monstre_class
{
    private string $id;
    private string $name;
    private string $description;
    private array $type;
    private string $image;
    private int $heal_score;
    private int $defense_score;
    private int $attaque_score;
    private int $heads; 
    private bool $isHybride;
    private string $created_by;

    public function __construct(string $name, string $description, array $type, string $image, int $heal_score, int $defense_score, int $attaque_score, int $heads, bool $isHybride = false, string $created_by)
    {
        $this->setId();
        $this->setName($name);
        $this->setDescription($description);
        $this->setType($type);
        $this->setImage($image);
        $this->setHeal_score($heal_score);
        $this->setDefense_score($defense_score);
        $this->setAttaque_score($attaque_score);
        $this->setHeads($heads);
        $this->setIsHybride($isHybride);
        $this->setCreated_by($created_by);

    }


    /**
     * Get the value of id
     */ 
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId()
    {
        $id = uniqid('', true);
        $id = bin2hex(random_bytes(16));
        $this->id = $id;

        return $this;
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
     * Get the value of description
     */ 
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType() : array
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType(array $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of image
     */ 
    public function getImage() : string
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage(string $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of heal_score
     */ 
    public function getHeal_score() : int
    {
        return $this->heal_score;
    }

    /**
     * Set the value of heal_score
     *
     * @return  self
     */ 
    public function setHeal_score(int $heal_score)
    {
        $this->heal_score = $heal_score;

        return $this;
    }

    /**
     * Get the value of defense_score
     */ 
    public function getDefense_score() : int
    {
        return $this->defense_score;
    }

    /**
     * Set the value of defense_score
     *
     * @return  self
     */ 
    public function setDefense_score(int $defense_score)
    {
        $this->defense_score = $defense_score;

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
     * Get the value of isHybride
     */ 
    public function getIsHybride() : bool
    {
        return $this->isHybride;
    }

    /**
     * Set the value of isHybride
     *
     * @return  self
     */ 
    public function setIsHybride(bool $isHybride)
    {
        $this->isHybride = $isHybride;

        return $this;
    }

    /**
     * Get the value of created_by
     */ 
    public function getCreated_by() : string
    {
        return $this->created_by;
    }

    /**
     * Set the value of created_by
     *
     * @return  self
     */ 
    public function setCreated_by(string $created_by)
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * Get the value of attaque_score
     */ 
    public function getAttaque_score() : int
    {
        return $this->attaque_score;
    }

    /**
     * Set the value of attaque_score
     *
     * @return  self
     */ 
    public function setAttaque_score(int $attaque_score)
    {
        $this->attaque_score = $attaque_score;

        return $this;
    }
}