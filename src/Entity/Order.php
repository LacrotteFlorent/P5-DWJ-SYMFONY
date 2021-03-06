<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="array")
     */
    private $list = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $pickupDate;

    private $listWithData = [];

    private $totalPrice;

    private $totalNumberOfProducts;

    private $pickupTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getList(): ?array
    {
        return $this->list;
    }

    public function setList(array $list): self
    {
        $this->list = $list;

        return $this;
    }

    public function getListWithData(): ?array
    {
        return $this->listWithData;
    }

    public function setListWithData(array $listWithData): self
    {
        $this->listWithData = $listWithData;

        return $this;
    }

    public function getPickupDate(): ?\DateTimeInterface
    {
        return $this->pickupDate;
    }

    public function setPickupDate(?\DateTimeInterface $pickupDate): self
    {
        $this->pickupDate = $pickupDate;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTotalNumberOfProducts(): ?int
    {
        return $this->totalNumberOfProducts;
    }

    public function setTotalNumberOfProducts(?int $totalNumberOfProducts): self
    {
        $this->totalNumberOfProducts = $totalNumberOfProducts;

        return $this;
    }

    public function getPickupTime(): ?\DateTimeInterface
    {
        return $this->pickupTime;
    }

    public function setPickupTime(?\DateTimeInterface $pickupTime): self
    {
        $this->pickupTime = $pickupTime;

        return $this;
    }

}
