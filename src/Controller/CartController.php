<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_show")
     */
    public function show()
    {
        return $this->render('cart/cart.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }
}
