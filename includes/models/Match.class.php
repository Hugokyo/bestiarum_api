<?php 

class Match_class{

    private string $monstre1;
    private string $monstre2;
    private string $result;
    private string $uuid;

    public function __construct(string $result, string $monstre1, string $monstre2){
        $this->setMonstre1($monstre1);
        $this->setMonstre2($monstre2);
        $this->setResult($result);
        $this->getUuid();
    }

    /**
     * Get the value of monstre1
     */ 
    public function getMonstre1() : string
    {
        return $this->monstre1;
    }

    /**
     * Set the value of monstre1
     *
     * @return  self
     */ 
    public function setMonstre1(string $monstre1)
    {
        $this->monstre1 = $monstre1;

        return $this;
    }

    /**
     * Get the value of monstre2
     */ 
    public function getMonstre2() : string
    {
        return $this->monstre2;
    }

    /**
     * Set the value of monstre2
     *
     * @return  self
     */ 
    public function setMonstre2(string $monstre2)
    {
        $this->monstre2 = $monstre2;

        return $this;
    }

    /**
     * Get the value of result
     */ 
    public function getResult() : string 
    {
        return $this->result;
    }

    /**
     * Set the value of result
     *
     * @return  self
     */ 
    public function setResult(string $result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get the value of uuid
     */ 
    public function getUuid() : string
    {
        $id = uniqid('', true);
        $id = bin2hex(random_bytes(16));
        $this->uuid = $id;

        return $this->uuid;
    }
}