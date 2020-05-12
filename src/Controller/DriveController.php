<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class DriveController extends AbstractController
{
    /**
     * @Route("/drive", name="drive_show")
     */
    public function show()
    {
        $repo = $this->getDoctrine()->getRepository(Product::class);

        $products = $repo->findAll();

        return $this->render('drive/drive.html.twig', [
            'controller_name'   => 'DriveController',
            "products"          => $products,
        ]);
    }
}
