<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    /**
     * @Route("/newOrder", name="orderManager_newOrder")
     */
    public function newOrder(Request $request)
    {
        $order = new Order;
        $formOrder = $this->createForm(OrderType::class, $order);
        $formOrder->handleRequest($request);

        if($formSearch->isSubmitted() &&  $formSearch->isValid()){
            
        }
    }
}
