<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
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
    public function add(Request $request)
    {
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repoProduct->findAll();

        $repoCategory = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repoCategory->findAll();

        $product = new Product;
        
        $formProduct = $this->createForm(ProductType::class, $product);
        $formProduct->handleRequest($request);

        if( $formProduct->isSubmitted() &&  $formProduct->isValid()){
            $product = $formProduct->getData();
            $product->setCreatedAt(new Datetime());
            if($product->enabled == true){
                $product->setEnabledSince(new Datetime());
            }
            else {
                $product->setEnabledSince(null);
            }
            
        }

        return $this->render('product_manager/productManagerAdd.html.twig', [
            'products'          => $products,
            'formProduct'       => $formProduct->createView(),
        ]);
    }
}
