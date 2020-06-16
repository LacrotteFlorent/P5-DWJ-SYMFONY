<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*
        for($i = 0; $i <= 10; $i++){
            $product = new Product();
            $product->setName("Légume". $i)
                    ->setEnabled(true)
                    ->setDescription("Ceci est un légume BIO")
                    ->setCreatedAt(new \DateTime())
                    ->setEnabledSince(new \DateTime())
                    ->setSeason("Toute l'année")
                    ->setCategory("CategoryOne")
                    ->setImgId(30+$i);
            
            $manager->persist($product);
        }
        for($i = 1; $i <= 10; $i++){
            $product = new Product();
            $product->setName("Légume". $i)
                    ->setEnabled(true)
                    ->setDescription("Ceci est un légume BIO")
                    ->setCreatedAt(new \DateTime())
                    ->setEnabledSince(new \DateTime())
                    ->setSeason("Toute l'année")
                    ->setCategory("CategoryTwo")
                    ->setImgId(20+$i);
            
            $manager->persist($product);
        }
        
        $manager->flush();
        */
    }
}
