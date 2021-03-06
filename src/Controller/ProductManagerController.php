<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Search;
use App\Entity\Picture;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Service\FileUploader;
use App\Form\Type\ProductSearchType;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductManagerController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/product_manager/{page}", requirements={"page" = "\d+"}, name="productManager_show")
     */
    public function show(Request $request, $page = null, $nbProductsByPage)
    {
        if($this->security->isGranted('ROLE_ADMIN'))
        {
            $repoProduct = $this->getDoctrine()->getRepository(Product::class);
            $numberOfProducts = $repoProduct->productLenght();

            $productSearch = new Search;
            $formSearch = $this->createForm(ProductSearchType::class, $productSearch);
            $formSearch->handleRequest($request);

            if($formSearch->isSubmitted() &&  $formSearch->isValid()){
                $products = $repoProduct->findBySearchAndPaginator($productSearch, $page, $nbProductsByPage, false);
            }
            else {
                $products = $repoProduct->findAllAndPaginator($page, $nbProductsByPage, false);
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

        return $this->redirectToRoute("home_show");
    }

    /**
     * @Route("/product_manager/modify/{id}", requirements={"id" = "\d+"}, name="productManager_modify")
     * @Route("/product_manager/modify", name="productManager_modify_no_id")
     */
    public function modify(Product $product=null, Request $request, FileUploader $fileUploader)
    {
        if($this->security->isGranted('ROLE_ADMIN'))
        {
            $repoProduct = $this->getDoctrine()->getRepository(Product::class);
            $numberOfProducts = $repoProduct->productLenght();

            if($product) {
                $product = $repoProduct->find($product->getId());

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

                    $manager = $this->getDoctrine()->getManager();
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

        return $this->redirectToRoute("home_show");
    }

    /**
     * @Route("/product_manager/add", name="productManager_add")
     */
    public function add(Request $request, FileUploader $fileUploader)
    {
        if($this->security->isGranted('ROLE_ADMIN'))
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
                $picture = new Picture;
                $picture->setName('defaultImg');
                if($pictureFile){
                    $pictureFileName = $fileUploader->upload($pictureFile, $product->getName());
                    $picture->setName($pictureFileName);
                    $picture->setAlt('photo de ' . $product->getName());
                }
                else {
                    $picture->setName('defaultImg.png');
                    $picture->setAlt('Image par defaut');
                }
                $product->setPicture($picture);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();

                return $this->redirectToRoute('productManager_show', ['page' => '1']);
            }

            return $this->render('product_manager/productManagerAdd.html.twig', [
                'numberOfProducts'  => $numberOfProducts,
                'formProduct'       => $formProduct->createView(),
            ]);
        }

        return $this->redirectToRoute("home_show");
    }

    /**
     * @Route("/product_manager/delete/{id}", requirements={"id" = "\d+"}, name="productManager_delete")
     */
    public function delete(Product $product)
    {
        if($this->security->isGranted('ROLE_ADMIN'))
        {
            $repoOrder = $this->getDoctrine()->getRepository(Order::class);
            $orders = $repoOrder->findAll();
            foreach($orders as $order){
                $orderList = $order->getList();
                foreach($orderList as $id => $quantity){
                    if($id === $product->getId()){
                        return $this->render('product_manager/productManagerError.html.twig');
                    }
                }
            }
            
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($product);
            $manager->flush();

            if($product->getPicture()->getName() != 'defaultImg.png'){
                unlink('../public/img/products/'.$product->getPicture()->getName());
            }

            return $this->redirectToRoute('productManager_show', ['page' => '1']);
        }

        return $this->redirectToRoute("home_show");
    }
}
