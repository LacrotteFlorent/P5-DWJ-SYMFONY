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
     * @var float $minPrice
     */
    protected $minPrice;

    /**
     * @var string $search
     */
    protected $search;

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
        $this->seasons = $seasons;
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

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search = null)
    {
        $this->search = $search;
    }

}