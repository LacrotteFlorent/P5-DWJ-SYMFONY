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

        $repoCategories = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repoCategories->findAll();

        $repoSeasons = $this->getDoctrine()->getRepository(Season::class);
        $seasons = $repoSeasons->findAll();

        $filter = new Filter;
        $formFilter = $this->createForm(FilterType::class, $filter);
        $formFilter->handleRequest($request);
        
        if($formFilter->isSubmitted() && $formFilter->isValid()) {
            $this->session->set('filters', $filter);
            $repoProduct = $this->getDoctrine()->getRepository(Product::class);
            $products = $repoProduct->findByFiltersAndPaginator($filter, $page, $nbProductsByPage);
        }
        elseif($this->session->get('filters') !== null){
            $repoProduct = $this->getDoctrine()->getRepository(Product::class);
            $products = $repoProduct->findByFiltersAndPaginator($this->session->get('filters'), $page, $nbProductsByPage);
        }
        else {
            $repoProduct = $this->getDoctrine()->getRepository(Product::class);
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
            'categories'        => $categories,
            'seasons'           => $seasons,
            'formFilter'        => $formFilter->createView(),
            'paginate'          => $paginate,
        ]);
    }
    
}
