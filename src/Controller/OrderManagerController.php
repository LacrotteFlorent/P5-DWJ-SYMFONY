<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/order_manager/list", requirements={"page" = "\d+"}, name="orderManager_show")
     */
    public function show()
    {
        if($this->security->isGranted('ROLE_ADMIN')){

            $repoOrder = $this->getDoctrine()->getRepository(Order::class);
            $repoProduct = $this->getDoctrine()->getRepository(Product::class);

            $orders = $repoOrder->findAll();
            $numberOfOrders = count($orders);

            $ordersWithData['orderList'] = [];
            foreach($orders as $order){
                $orderList = $order->getList();
                $orderWithData = [];
                $totalPrice = 0;
                $totalNumberOfProducts = 0;
                foreach($orderList as $id => $quantity) {
                    $product = $repoProduct->find($id);
                    $orderWithData[] = [
                        'product'   => $product,
                        'quantity'  => $quantity,
                    ];
                    $totalPrice = $totalPrice + $product->getPrice();
                    $totalNumberOfProducts++;
                }
                array_push($ordersWithData['orderList'], $orderWithData);
                $order->setListWithData($orderWithData);
                $order->setTotalNumberOfProducts($totalNumberOfProducts);
                $order->setTotalPrice($totalPrice);
            }

            return $this->render('order_manager/orderManagerList.html.twig', [
                'orders'            => $orders,
                'numberOfOrders'    => $numberOfOrders,
            ]);
        }

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/order_manager/details", requirements={"id" = "\d+"}, name="orderManager_showAndValid")
     * @Route("/order_manager/modify", name="orderManager_showAndValid_noId")
     */
    public function showAndValid(Order $order=null, Request $request)
    {
        if($this->security->isGranted('ROLE_ADMIN')){

            $repoOrder = $this->getDoctrine()->getRepository(Order::class);
            $repoProduct = $this->getDoctrine()->getRepository(Product::class);
            $numberOfOrders = $repoOrder->orderLenght();

            if($order){
                $order = $repoOrder->find($order->getId());

                $formValidOrder = $this->createForm(OrderType::class, $order);
                $formValidOrder->handleRequest($request);

                if($formValidOrder->isSubmitted() &&  $formValidOrder->isValid()){

                    // TODO une fois le formulaire validé, on passe valid à 1 et change la date si elle dois l'être
                    // on envois un mail au client pour confirmer la date d'enlèvement de la commande
                    return $this->redirectToRoute('orderManager_show');
                }

                $orderList = $order->getList();
                $orderWithData = [];
                $totalPrice = 0;
                $totalNumberOfProducts = 0;
                foreach($orderList as $id => $quantity) {
                    $product = $repoProduct->find($id);
                    $orderWithData[] = [
                        'product'   => $product,
                        'quantity'  => $quantity,
                    ];
                    $totalPrice = $totalPrice + $product->getPrice();
                    $totalNumberOfProducts++;
                }
                $order->setListWithData($orderWithData);
                $order->setTotalNumberOfProducts($totalNumberOfProducts);
                $order->setTotalPrice($totalPrice);

                return $this->render('order_manager/orderManagerDetails.html.twig', [
                    'orders'            => $order,
                    'formOrder'         => $formValidOrder,
                    'numberOfOrders'    => $numberOfOrders,
                    'noId'              => false,
                ]);
            }

            return $this->render('order_manager/orderManagerDetails.html.twig', [
                'numberOfOrders'    => $numberOfOrders,
                'noId'              => true,
            ]);
        }

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/order_manager/delete/{id}", requirements={"id" = "\d+"}, name="orderManager_delete")
     */
    public function delete(Order $order)
    {
        // TODO  supprimer la commande
    }

    /**
     * @Route("/newOrder", name="orderManager_newOrder")
     */
    public function newOrder(Request $request, SessionInterface $session)
    {
        if($this->security->isGranted('ROLE_USER'))
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

        return $this->redirectToRoute('cart_show');
    }
}
