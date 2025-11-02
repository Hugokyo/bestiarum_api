<?php 

class Hybrid_class extends Monstre_class
{
    private string $parent1;
    private string $parent2;

    public function __construct(string $name, string $description, array $type, string $image, int $heal_score, int $defense_score, int $attaque_score, int $heads, string $created_by, string $parent1, string $parent2)
    {
        parent::__construct($name, $description, $type, $image, $heal_score, $defense_score, $attaque_score, $heads, true, $created_by);
        $this->setParent1($parent1);
        $this->setParent2($parent2);
    }


    /**
     * Get the value of parent1
     */ 
    public function getParent1() : string
    {
        return $this->parent1;
    }

    /**
     * Set the value of parent1
     *
     * @return  self
     */ 
    public function setParent1(string $parent1)
    {
        $this->parent1 = $parent1;

        return $this;
    }

    /**
     * Get the value of parent2
     */ 
    public function getParent2() : string
    {
        return $this->parent2;
    }

    /**
     * Set the value of parent2
     *
     * @return  self
     */ 
    public function setParent2(string $parent2)
    {
        $this->parent2 = $parent2;

        return $this;
    }
}