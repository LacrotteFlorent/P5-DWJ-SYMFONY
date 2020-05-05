<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i <= 10; $i++){
            $article = new Article();
            $article->setName("Légume". $i)
                    ->setEnabled(true)
                    ->setDescription("Ceci est un légume BIO")
                    ->setCreatedAt(new \DateTime())
                    ->setEnabledSince(new \DateTime())
                    ->setSeason("Toute l'année")
                    ->setCategory("CategoryOne")
                    ->setImgId(30+$i);
            
            $manager->persist($article);
        }
        for($i = 1; $i <= 10; $i++){
            $article = new Article();
            $article->setName("Légume". $i)
                    ->setEnabled(true)
                    ->setDescription("Ceci est un légume BIO")
                    ->setCreatedAt(new \DateTime())
                    ->setEnabledSince(new \DateTime())
                    ->setSeason("Toute l'année")
                    ->setCategory("CategoryTwo")
                    ->setImgId(20+$i);
            
            $manager->persist($article);
        }
        
        $manager->flush();
    }
}
