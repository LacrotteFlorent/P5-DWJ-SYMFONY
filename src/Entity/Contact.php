<?php

namespace App\Entity;

class Contact
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
     * @var string $email
     */
    protected $email;

    /**
     * @var string $reason
     */
    protected $reason;

    /**
     * @var string $subject
     */
    protected $subject;

    /**
     * @var string $message
     */
    protected $message;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

}