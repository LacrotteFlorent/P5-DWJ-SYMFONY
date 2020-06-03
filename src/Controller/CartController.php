<?php

namespace App\Controller;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\AddCartType;
use App\Entity\AddCart;


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
