<?php

class Type
{
    private string $id;
    private string $name;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * Get the value of uuid
     */ 
    public function getId(): string
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
}