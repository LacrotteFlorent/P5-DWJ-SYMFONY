<?php

namespace App\Entity;

class SignUp
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $firstName
     */
    protected $firstName;

    /**
     * @var string $mail
     */
    protected $mail;

    /**
     * @var string $password
     */
    protected $password;

    /**
     * @var int $mobileNumber
     */
    protected $mobileNumber;

    /**
     * @var int $phoneNumber
     */
    protected $phoneNumber;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name = null)
    {
        $this->name = $name;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName = null)
    {
        $this->firstName = $firstName;
    }

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

    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    public function setMobileNumber($mobileNumber = null)
    {
        $this->mobileNumber = $mobileNumber;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber = null)
    {
        $this->phoneNumber = $phoneNumber;
    }

}