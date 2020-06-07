<?php

namespace App\Controller;

use App\Entity\AddCart;
use App\Entity\Product;
use App\Form\Type\AddCartType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_show")
     */
    public function home() 
    {
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
        //dd($formsAddCart);

        return $this->render('home/home.html.twig', [
            'products'          => $products,
            'formsAddCart'      => $formsAddCart,
        ]);
    }
}
