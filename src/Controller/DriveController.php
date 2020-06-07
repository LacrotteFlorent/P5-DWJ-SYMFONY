<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\FilterType;
use App\Form\Type\AddCartType;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Season;
use App\Entity\Filter;
use App\Entity\AddCart;


class DriveController extends AbstractController
{
    /**
     * @Route("/drive/{page}", requirements={"page" = "\d+"}, name="drive_show")
     * @param   int $page
     * @return  array render for twig
     */
    public function show(Request $request, $page, $nbProductsByPage, SessionInterface $session)
    {
        $filter = new Filter;
        $formFilter = $this->createForm(FilterType::class, $filter)->handleRequest($request);
        
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);

        if($formFilter->isSubmitted() && $formFilter->isValid()) {
            $session->set('filters', $filter);
            $products = $repoProduct->findByFiltersAndPaginator($filter, 1, $nbProductsByPage);
            return $this->redirectToRoute('drive_show', ['page' => '1']);
        }
        elseif($session->get('filters') !== null){
            $products = $repoProduct->findByFiltersAndPaginator($session->get('filters'), $page, $nbProductsByPage);
        }
        else {
            $products = $repoProduct->findAllAndPaginator($page, $nbProductsByPage);
        }

        $addCart = new AddCart;
        $formsAddCart = [];
        foreach($products as $product){
            $addCart->setProductId($product->getId());
            $addCart->setProductPage($page);
            $form = $this->createForm(AddCartType::class, $addCart);
            $formsAddCart[$product->getId()] = $form->createView();
        }

        $paginate = [
            'page'          => $page,
            'nbPages'       => ceil(count($products) / $nbProductsByPage),
            'nameRoute'     => 'drive_show',
            'paramsRoute'   => []
        ];

        return $this->render('drive/drive.html.twig', [
            'products'          => $products,
            'formFilter'        => $formFilter->createView(),
            'paginate'          => $paginate,
            'formsAddCart'      => $formsAddCart,
        ]);
    }

    /**
     * @Route("/drive/clearFilter", name="drive_clearFilter")
     * @param   int $page
     * @return  array render for twig
     */
    public function clearFilter()
    {
        $this->session->remove('filters');
        
        return $this->redirectToRoute('drive_show', ['page' => '1']);
    }
    
}
