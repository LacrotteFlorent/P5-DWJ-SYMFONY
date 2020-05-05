<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_show")
     */
    public function home() {
        return $this->render('home/home.html.twig', [
            'title'     => "Titre",
            'age'       => 31,
        ]);
    }
}
