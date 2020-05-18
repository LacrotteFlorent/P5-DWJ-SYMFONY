<?php

namespace App\Entity;

class Filter
{
    /**
     * @var array $categories
     */
    protected $categories;

    /**
     * @var array $seasons
     */
    protected $seasons;

    /**
     * @var float $maxPrice
     */
    protected $maxPrice;

    /**
     * @var float minPrice
     */
    protected $minPrice;



    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories($categories = null)
    {
        $this->categories = $categories;
    }

    public function getSeasons()
    {
        return $this->seasons;
    }

    public function setSeasons($seasons = null)
    {
        $this->seasons = array_push($seasons);
    }

    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    public function setMaxPrice($maxPrice = null)
    {
        $this->maxPrice = $maxPrice;
    }

    public function getMinPrice()
    {
        return $this->minPrice;
    }

    public function setMinPrice($minPrice = null)
    {
        $this->minPrice = $minPrice;
    }
}