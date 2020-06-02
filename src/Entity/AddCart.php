<?php

namespace App\Entity;

class AddCart
{
    /**
     * @var string $productId
     */
    protected $productId;

    /**
     * @var int $productNumber
     */
    protected $productNumber;

    /**
     * @var int $productPage
     */
    protected $productPage;

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getProductNumber()
    {
        return $this->productNumber;
    }

    public function setProductNumber($productNumber)
    {
        $this->productNumber = $productNumber;
    }

    public function getProductPage()
    {
        return $this->productPage;
    }

    public function setProductPage($productPage)
    {
        $this->productPage = $productPage;
    }
}