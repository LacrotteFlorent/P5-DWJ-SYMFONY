<?php

namespace App\Entity;

class Search
{
    
    /**
     * @var string $search
     */
    protected $search;

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search = null)
    {
        $this->search = $search;
    }

}