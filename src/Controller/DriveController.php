<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Category;

class DriveController extends AbstractController
{
    /**
     * @Route("/drive", name="drive_show")
     */
    public function show()
    {
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repoProduct->findAll();

        $repoCategories = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repoCategories->findAll();
        //dd($categories);

        return $this->render('drive/drive.html.twig', [
            'products'          => $products,
            'categories'        => $categories,
        ]);
    }
    
    /**
     * @Route("/drive", name="drive_showByFilter{filter}")
     */
    public function showByFilter()
    {
        $repoCategories = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repoCategories->findAll();

        $repoProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repoProduct->findAll();

        return $this->render('drive/drive.html.twig', [
            'products'          => $products,
            'categories'        => $categories,
        ]);
    }
}
