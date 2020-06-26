<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LegalDisclaimerController extends AbstractController
{
    /**
     * @Route("/legaldisclaimer/flaticons", name="flaticons_show")
     */
    public function show()
    {
        return $this->render('legal_disclaimer/flaticons.html.twig');
    }
}
