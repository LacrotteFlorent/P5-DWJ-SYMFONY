<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_show")
     */
    public function show()
    {
        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
