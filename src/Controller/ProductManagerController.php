<?php

namespace App\Controller;

use App\Entity\Search;
use App\Entity\Picture;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Service\FileUploader;
use App\Form\Type\ProductSearchType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductManagerController extends AbstractController
{
    /**
     * @Route("/product_manager/{page}", requirements={"page" = "\d+"}, name="productManager_show")
     */
    public function show(Request $request, $page = null, $nbProductsByPage)
    {
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);
        $numberOfProducts = $repoProduct->productLenght();

        $productSearch = new Search;
        $formSearch = $this->createForm(ProductSearchType::class, $productSearch);
        $formSearch->handleRequest($request);

        if($formSearch->isSubmitted() &&  $formSearch->isValid()){
            $products = $repoProduct->findBySearchAndPaginator($productSearch, $page, $nbProductsByPage);
        }
        else {
            $products = $repoProduct->findAllAndPaginator($page, $nbProductsByPage);
        }

        $paginate = [
            'page'          => $page,
            'nbPages'       => ceil(count($products) / $nbProductsByPage),
            'nameRoute'     => 'productManager_show',
            'paramsRoute'   => []
        ];

        return $this->render('product_manager/productManagerSearch.html.twig', [
            'products'          => $products,
            'paginate'          => $paginate,
            'formSearch'        => $formSearch->createView(),
            'numberOfProducts'  => $numberOfProducts,
        ]);
    }

    /**
     * @Route("/product_manager/modify/{id}", requirements={"id" = "\d+"}, name="productManager_modify")
     * @Route("/product_manager/modify", name="productManager_modify_no_id")
     */
    public function modify(Product $product=null, Request $request, ManagerRegistry $managerRegistry, FileUploader $fileUploader)
    {
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);
        $numberOfProducts = $repoProduct->productLenght();

        if($product) {
            $product  = $repoProduct->find($product->getId());

            $formProduct = $this->createForm(ProductType::class, $product);
            $formProduct->handleRequest($request);

            if($formProduct->isSubmitted() &&  $formProduct->isValid()){
                if($formProduct['enabled']->getData() == true){
                    $product->setEnabledSince(new \Datetime);
                }

                // Upload picture and sock in /public/img/products/...
                $pictureFile = $formProduct['picture']->getData();
                if($pictureFile){
                    $pictureFileName = $fileUploader->upload($pictureFile, $product->getName());
                    $picture = $product->getPicture();
                    $picture->setName($pictureFileName);
                    $picture->setAlt('photo de ' . $product->getName());
                    $product->setPicture($picture);
                }

                $manager = $managerRegistry->getManager();
                $manager->persist($product);
                $manager->flush();

                return $this->redirectToRoute('productManager_show', ['page' => '1']);
            }

            return $this->render('product_manager/productManagerModify.html.twig', [
                'numberOfProducts'  => $numberOfProducts,
                'formProduct'       => $formProduct->createView(),
                'noId'              => false,
            ]);
        }

        return $this->render('product_manager/productManagerModify.html.twig', [
            'numberOfProducts'  => $numberOfProducts,
            'noId'              => true,
        ]);
    }

    /**
     * @Route("/product_manager/add", name="productManager_add")
     */
    public function add(Request $request, ManagerRegistry $managerRegistry, FileUploader $fileUploader)
    {
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);
        $numberOfProducts = $repoProduct->productLenght();

        $product = new Product;
        $formProduct = $this->createForm(ProductType::class, $product);
        $formProduct->handleRequest($request);

        if( $formProduct->isSubmitted() &&  $formProduct->isValid()){
            $product->setCreatedAt(new \Datetime);
            if($formProduct['enabled']->getData() == true){
                $product->setEnabledSince(new \Datetime);
            }
            else {
                $product->setEnabledSince(null);
            }

            // Upload picture and sock in /public/img/products/...
            $pictureFile = $formProduct['picture']->getData();
            if($pictureFile){
                $pictureFileName = $fileUploader->upload($pictureFile);
                $picture = new Picture;
                $picture->setName($pictureFileName);
                $picture->setAlt('photo de ' . $product->getName());
                $product->setPicture($picture);
            }

            $manager = $managerRegistry->getManager();
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('productManager_show', ['page' => '1']);
        }

        return $this->render('product_manager/productManagerAdd.html.twig', [
            'numberOfProducts'  => $numberOfProducts,
            'formProduct'       => $formProduct->createView(),
        ]);
    }

    /**
     * @Route("/product_manager/delete/{id}", requirements={"id" = "\d+"}, name="productManager_delete")
     */
    public function delete(Product $product, Request $request, ManagerRegistry $managerRegistry)
    {
        $manager = $managerRegistry->getManager();
        $manager->remove($product);
        $manager->flush();

        unlink('../public/img/products/'.$product->getPicture()->getName());

        return $this->redirectToRoute('productManager_show', ['page' => '1']);
    }
}
