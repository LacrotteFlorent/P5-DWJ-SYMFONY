<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderManagerController extends AbstractController
{
    /**
     * @Route("/order_manager/{page}", requirements={"page" = "\d+"}, name="orderManager_show")
     */
    public function show()
    {
        return $this->render('order_manager/orderManagerList.html.twig', [
            'controller_name' => 'orderManagerController',
        ]);
    }
}
