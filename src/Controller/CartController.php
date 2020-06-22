<?php

namespace App\Controller;
use App\Entity\Order;
use App\Entity\AddCart;
use App\Entity\Product;
use App\Form\OrderType;
use App\Form\Type\AddCartType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CartController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(SessionInterface $session, ProductRepository $productRepo)
    {
        if($this->security->isGranted('ROLE_USER'))
        {

            $cart = $session->get('cart', []);
            $cartWithData = [];
            foreach($cart as $id => $quantity) {
                $cartWithData[] = [
                    'product'   => $productRepo->find($id),
                    'quantity'  => $quantity,
                ];
            }

            $repo = $this->getDoctrine()->getRepository(Product::class);

            $products = $repo->findAllWithLimit(3);

            $addCart = new AddCart;
            $formsAddCart = [];
            foreach($products as $product){
                $addCart->setProductId($product->getId());
                $addCart->setProductPage(1);
                $form = $this->createForm(AddCartType::class, $addCart);
                $formsAddCart[$product->getId()] = $form->createView();
            }

            $order = new Order;
            $order->setPickupDate(new \Datetime('Europe/Paris'));
            $order->setPickupTime($order->getPickupDate());
            $formOrder = $this->createForm(OrderType::class, $order, [
                'action' => $this->generateUrl('orderManager_newOrder'),
                'method' => 'POST',
            ]);

            return $this->render('cart/cart.html.twig', [
                'cart'              => $cartWithData,
                'products'          => $products,
                'formsAddCart'      => $formsAddCart,
                'formOrder'         => $formOrder->createView(),
            ]);

        }

        return $this->redirectToRoute("home_show");
    }

    /**
     * @Route("/cart/add", name="cart_add")
     */
    public function add(Request $request, SessionInterface $session)
    {
        $addCart = new AddCart;
        $formAddCart = $this->createForm(AddCartType::class, $addCart)->handleRequest($request);
        $addCart = $formAddCart->getData();
        $cart = $session->get('cart', []);

        if(!empty($cart[$addCart->getProductId()])) {
            $cart[$addCart->getProductId()] = $cart[$addCart->getProductId()] + $addCart->getProductNumber();
        }
        else {
            $cart[$addCart->getProductId()] = $addCart->getProductNumber();
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute("drive_show",["page" => $addCart->getProductPage(), "_fragment" => $addCart->getProductId()]);
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if(!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart/less/{id}", name="cart_less")
     */
    public function less($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if((!empty($cart[$id])) && $cart[$id] >= 2) {
            $cart[$id] = $cart[$id] - 1;
        }
        elseif((!empty($cart[$id])) && $cart[$id] >= 1) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart/more/{id}", name="cart_more")
     */
    public function more($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if((!empty($cart[$id]))) {
            $cart[$id] = $cart[$id] + 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute("cart_show");
    }

}
