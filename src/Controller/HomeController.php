<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_show")
     */
    public function home() 
    {
        $repo = $this->getDoctrine()->getRepository(Product::class);

        $products = $repo->findAllWithLimit(3);


        return $this->render('home/home.html.twig', [
            'products'       => $products,
        ]);
    }
}
