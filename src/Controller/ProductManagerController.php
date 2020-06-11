<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Entity\Picture;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductManagerController extends AbstractController
{
    /**
     * @Route("/product_manager/{page}", requirements={"page" = "\d+"}, name="productManager_show")
     */
    public function show(Request $request, $page = null, $nbProductsByPage)
    {
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);

        $products = $repoProduct->findAllAndPaginator($page, $nbProductsByPage);

        $paginate = [
            'page'          => $page,
            'nbPages'       => ceil(count($products) / $nbProductsByPage),
            'nameRoute'     => 'productManager_show',
            'paramsRoute'   => []
        ];

        return $this->render('product_manager/productManagerSearch.html.twig', [
            'products'          => $products,
            'paginate'          => $paginate,
        ]);
    }

    /**
     * @Route("/product_manager/modify/{id}", requirements={"page" = "\d+"}, name="productManager_modify")
     */
    public function modify(Request $request, $page, $nbProductsByPage)
    {
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);

        $products = $repoProduct->findAllAndPaginator($page, $nbProductsByPage);

        return $this->render('product_manager/productManagerSearch.html.twig', [
            'products'          => $products,
        ]);
    }

    /**
     * @Route("/product_manager/add", name="productManager_add")
     */
    public function add(Request $request, ManagerRegistry $managerRegistry, FileUploader $fileUploader)
    {
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repoProduct->findAll();

        $product = new Product;
        
        $formProduct = $this->createForm(ProductType::class, $product);
        
        $formProduct->handleRequest($request);
        dump($product);

        if( $formProduct->isSubmitted() &&  $formProduct->isValid()){
            $product->setCreatedAt(new \Datetime);
            if($formProduct['enabled']->getData() == true){
                $product->setEnabledSince(new \Datetime);
            }
            else {
                $product->setEnabledSince(null);
            }

            //$directory = '/img/products/';
            //$file = $formProduct['picture']->getData();
            //// compute a random name and try to guess the extension (more secure)
            //$extension = $file->guessExtension();
            //if (!$extension) {
            //    // extension cannot be guessed
            //    $extension = 'bin';
            //}
            //$pictureName = rand(1, 99999).'.'.$extension;
            //$file->move($directory, $pictureName);
            
            // Upload picture and sock in /public/img/products/...
            $pictureFile = $formProduct['picture']->getData();
            if($pictureFile){
                $pictureFileName = $fileUploader->upload($pictureFile);
                $picture = new Picture;
                $picture->setName($pictureFileName);
                $picture->setAlt('photo de ' . $product->getName());
                $product->setPicture($picture);
            }

            //$picture = new Picture;
            //$picture->setName($pictureName);
            //$picture->setAlt('photo de ' .$product->getName());
            //$product->setPicture($picture);
            
            dump($picture);
            dump($product);
            $manager = $managerRegistry->getManager();
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('productManager_show', ['page' => '1']);
        }

        return $this->render('product_manager/productManagerAdd.html.twig', [
            'products'          => $products,
            'formProduct'       => $formProduct->createView(),
        ]);
    }
}
