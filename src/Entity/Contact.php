<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class Contact
{
    /**
     * @var string $name
     * @Assert\Length(min="3", minMessage="Votre nom doit faire minimum 3 caractères")
     */
    protected $name;

    /**
     * @var string $firstName
     * @Assert\Length(min="3", minMessage="Votre prénom doit faire minimum 3 caractères")
     */
    protected $firstName;

    /**
     * @var string $email
     * @Assert\NotBlank
     * @Assert\Email()
     */
    protected $email;

    /**
     * @var string $reason
     * @Assert\NotBlank
     */
    protected $reason;

    /**
     * @var string $subject
     * @Assert\NotBlank
     */
    protected $subject;

    /**
     * @var string $message
     * @Assert\NotBlank
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