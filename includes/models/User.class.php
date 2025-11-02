<?php 

class User 
{
    private string $id;
    private string $username;
    private string $email;
    private string $password;

    public function __construct(string $username = '', string $email = '', string $password = '')
    {
        $this->setId();
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setPassword($password);
    }


    /**
     * Get the value of username
     */ 
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    /**
     * Get the value of id
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
}