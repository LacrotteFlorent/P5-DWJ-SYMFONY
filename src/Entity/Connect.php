<?php

namespace App\Entity;

class Connect
{
    /**
     * @var string $mail
     */
    protected $mail;

    /**
     * @var string $password
     */
    protected $password;

    public function getMail()
    {
        return $this->mail;
    }

    public function setMail($mail = null)
    {
        $this->mail = $mail;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password = null)
    {
        $this->password = $password;
    }

}