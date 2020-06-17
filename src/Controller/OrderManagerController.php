<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Order;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderManagerController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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
    public function newOrder(Request $request, SessionInterface $session)
    {
        if($this->security->isGranted('ROLE_ADMIN'))
        {
            $order = new Order;
            $formOrder = $this->createForm(OrderType::class, $order);
            $formOrder->handleRequest($request);

            if($formOrder->isSubmitted() &&  $formOrder->isValid()){
                $pickupDate = $order->getPickupDate();
                $time = $formOrder['pickupTime']->getData();
                $time = getDate($time->getTimestamp());
                $timeInterval = new DateInterval('PT' . $time['hours'] . 'H' . $time['minutes'] . 'M' . $time['seconds'] . 'S');
                $pickupDate->add($timeInterval);

                $order->setValid(false);
                $order->setCreatedAt(new \Datetime());
                $order->setUser($this->getUser());
                $order->setList($session->get('cart', []));

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($order);
                $manager->flush();

                return $this->redirectToRoute('cart_show');
            }

            return $this->redirectToRoute('cart_show');
        }
    }
}
