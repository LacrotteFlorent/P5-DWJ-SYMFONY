<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProducerController extends AbstractController
{
    /**
     * @Route("/producteur", name="producer")
     */
    public function show()
    {
        return $this->render('producer/producer.html.twig');
    }
}
