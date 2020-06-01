<?php

namespace App\Controller;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\AddCart;
use App\Form\Type\AddCartType;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(SessionInterface $session, ProductRepository $productRepo)
    {
        $cart = $session->get('cart', []);
        $cartWithData = [];
        foreach($cart as $id => $quantity) {
            $cartWithData[] = [
                'product'   => $productRepo->find($id),
                'quantity'  => $quantity,
            ];
        }
        dump($cartWithData);
        return $this->render('cart/cart.html.twig', [
            'cart'  => $cartWithData,
        ]);
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

        return $this->redirectToRoute("cart_show");
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

}
