<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HowItWorksController extends AbstractController
{
    /**
     * @Route("/fonctionnement", name="fonctionnement_show")
     */
    public function show()
    {
        return $this->render('how_it_works/howItWorks.html.twig', [
            'controller_name' => 'HowItWorksController',
        ]);
    }
}
