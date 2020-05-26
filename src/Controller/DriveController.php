<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\FilterType;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Season;
use App\Entity\Filter;

class DriveController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/drive/{page}", requirements={"page" = "\d+"}, name="drive_show")
     * @param   int $page
     * @return  array render for twig
     */
    public function show(Request $request, $page)
    {
        $nbProductsByPage = 6;

        $filter = new Filter;
        $formFilter = $this->createForm(FilterType::class, $filter);
        $formFilter->handleRequest($request);
        
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);

        if($formFilter->isSubmitted() && $formFilter->isValid()) {
            $this->session->set('filters', $filter);
            $products = $repoProduct->findByFiltersAndPaginator($filter, 1, $nbProductsByPage);
            return $this->redirectToRoute('drive_show', ['page' => '1']);
        }
        elseif($this->session->get('filters') !== null){
            $products = $repoProduct->findByFiltersAndPaginator($this->session->get('filters'), $page, $nbProductsByPage);
        }
        else {
            $products = $repoProduct->findAllAndPaginator($page, $nbProductsByPage);
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

    /**
     * @Route("/drive/search", name="drive_search")
     * @param   int $page
     * @return  array render for twig
     */
    public function search(Request $request)
    {
        
        return $this->redirectToRoute('drive_show', ['page' => '1']);
    }
    
}
