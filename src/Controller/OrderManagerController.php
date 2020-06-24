<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use App\Form\OrderValidationType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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
     * @Route("/order_manager/list", name="orderManager_show")
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
                    $totalPrice = $totalPrice + ($product->getPrice() * $quantity);
                    $totalNumberOfProducts = $totalNumberOfProducts + (1 * $quantity);
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

        return $this->render('security/needConnect.html.twig');
    }

    /**
     * @Route("/order_manager/details/{id}", requirements={"id" = "\d+"}, name="orderManager_showAndValid")
     * @Route("/order_manager/details", name="orderManager_showAndValid_noId")
     */
    public function showAndValid(Order $order=null, Request $request, MailerInterface $mailer, $mailProducer)
    {
        if($this->security->isGranted('ROLE_ADMIN')){

            $repoOrder = $this->getDoctrine()->getRepository(Order::class);
            
            $numberOfOrders = $repoOrder->orderLenght();

            if($order){
                $order = $repoOrder->find($order->getId());
                $order->setPickupTime($order->getPickupDate());
                $formValidOrder = $this->createForm(OrderValidationType::class, $order);
                $formValidOrder->handleRequest($request);

                if($formValidOrder->isSubmitted() &&  $formValidOrder->isValid()){
                    $pickupDate = $order->getPickupDate();
                    $time = $formValidOrder['pickupTime']->getData();
                    $time = getDate($time->getTimestamp());
                    $timeInterval = new DateInterval('PT' . $time['hours'] . 'H' . $time['minutes'] . 'M' . $time['seconds'] . 'S');
                    $pickupDate->add($timeInterval);

                    $order->setValid(true);

                    $order = $this->hydrateOrderWithData($order);

                    $emailToCustomer = (new TemplatedEmail())
                    ->from($mailProducer)
                    ->to($order->getUser()->getEmail())
                    ->subject('Validation de votre commande : MangerPlusPrès.fr')
                    ->htmlTemplate('emails/orderManagerCustomer.html.twig')
                    ->context([
                        'order'         => $order,
                        'emailProducer' => $mailProducer,
                    ]);

                    $mailer->send($emailToCustomer);

                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($order);
                    $manager->flush();

                    return $this->redirectToRoute('orderManager_show');
                }

                $order = $this->hydrateOrderWithData($order);

                return $this->render('order_manager/orderManagerDetails.html.twig', [
                    'order'             => $order,
                    'formOrder'         => $formValidOrder->createView(),
                    'numberOfOrders'    => $numberOfOrders,
                    'noId'              => false,
                ]);
            }

            return $this->render('order_manager/orderManagerDetails.html.twig', [
                'numberOfOrders'    => $numberOfOrders,
                'noId'              => true,
            ]);
        }

        return $this->render('security/needConnect.html.twig');
    }

    /**
     * @param Order $order
     * @return Order Return order hydrate with products
     */
    private function hydrateOrderWithData($order)
    {
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);

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
    
        return $order;
    }

    /**
     * @Route("/order_manager/delete/{id}", requirements={"id" = "\d+"}, name="orderManager_delete")
     */
    public function delete(Order $order)
    {
        if($this->security->isGranted('ROLE_ADMIN'))
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($order);
            $manager->flush();

            return $this->redirectToRoute('orderManager_show');
        }

        return $this->render('security/needConnect.html.twig');
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
                if(empty($session->get('cart', []))){
                    return $this->redirectToRoute('cart_show');
                }
                $pickupDate = $order->getPickupDate();
                $time = $formOrder['pickupTime']->getData();
                $time = getDate($time->getTimestamp());
                $timeInterval = new DateInterval('PT' . $time['hours'] . 'H' . $time['minutes'] . 'M' . $time['seconds'] . 'S');
                $pickupDate->add($timeInterval);

                $order->setValid(false);
                $order->setCreatedAt(new \Datetime());
                $order->setUser($this->getUser());
                $order->setList($session->get('cart', []));
                $session->remove('cart');

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($order);
                $manager->flush();

                return $this->render('order_manager/orderSubmitted.html.twig');
            }

            return $this->redirectToRoute('cart_show');
        }

        return $this->render('security/needConnect.html.twig');
    }

    /**
     * @Route("/order_manager/more/{idProduct}/{idOrder}", requirements={"idProduct" = "\d+"}, requirements={"idOrder" = "\d+"}, name="orderManager_more")
     */
    public function moreProductInOrder($idProduct, $idOrder)
    {
        if($this->security->isGranted('ROLE_ADMIN')){
            $repoOrder = $this->getDoctrine()->getRepository(Order::class);
            $order = $repoOrder->find($idOrder);

            $list = $order->getList();
            $list[$idProduct] = ($list[$idProduct]+1);
            $order->setList($list);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($order);
            $manager->flush();

            return $this->redirectToRoute('orderManager_showAndValid', ['id'=>$idOrder]);
        }

        return $this->render('security/needConnect.html.twig');
    }

    /**
     * @Route("/order_manager/less/{idProduct}/{idOrder}", requirements={"idProduct" = "\d+"}, requirements={"idOrder" = "\d+"}, name="orderManager_less")
     */
    public function lessProductInOrder($idProduct, $idOrder)
    {
        if($this->security->isGranted('ROLE_ADMIN')){
            $repoOrder = $this->getDoctrine()->getRepository(Order::class);
            $order = $repoOrder->find($idOrder);

            $list = $order->getList();
            if($list[$idProduct] == 1){
                unset($list[$idProduct]);
            }
            else{
                $list[$idProduct] = ($list[$idProduct]-1);
            }
            $order->setList($list);
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($order);
            $manager->flush();

            return $this->redirectToRoute('orderManager_showAndValid', ['id'=>$idOrder]);
        }

        return $this->render('security/needConnect.html.twig');
    }

    /**
     * @Route("/order_manager/deleteProductInOrder/{idProduct}/{idOrder}", requirements={"idProduct" = "\d+"}, requirements={"idOrder" = "\d+"}, name="orderManager_deleteProductInOrder")
     */
    public function removeProductInOrder($idProduct, $idOrder)
    {
        if($this->security->isGranted('ROLE_ADMIN')){
            $repoOrder = $this->getDoctrine()->getRepository(Order::class);
            $order = $repoOrder->find($idOrder);

            $list = $order->getList();
            unset($list[$idProduct]);
            $order->setList($list);
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($order);
            $manager->flush();

            return $this->redirectToRoute('orderManager_showAndValid', ['id'=>$idOrder]);
        }

        return $this->render('security/needConnect.html.twig');
    }

}
