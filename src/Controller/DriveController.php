<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\FilterType;
use App\Entity\Product;
use App\Entity\Category;
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
        $form = $this->createFormBuilder($filter)
            ->add('maxPrice', IntegerType::class)
            ->add('minPrice', IntegerType::class)
            ->add('seasons', EntityType::class, [
                'class'         => 'App\Entity\Season',
                'choice_label'  => 'name',
                'multiple'      => true,
                'expanded'      => true,
                'attr'          => ['class' => 'test'],
            ])
            ->add('categories', EntityType::class, [
                'class'         => 'App\Entity\Category',
                'choice_label'  => 'title',
                'multiple'      => true,
                'expanded'      => true,
                'attr'          => ['class' => 'test'],
            ])
            ->add('changeFilter', SubmitType::class, [
                'attr'          => ['class' => 'btn btn-primary mx-1']
            ])
            ->getForm();
        
            $form->handleRequest($request);

            dump($filter);
            dump($this->session->get('filters'));
            
            if($form->isSubmitted() && $form->isValid()) {
                $this->session->set('filters', $filter);
                dump($this->session->get('filters'));
                $repoProduct = $this->getDoctrine()->getRepository(Product::class);
                $products = $repoProduct->findByFiltersAndPaginator($filter, $page, $nbProductsByPage);
            }
            elseif($this->session->get('filters') !== null){
                $repoProduct = $this->getDoctrine()->getRepository(Product::class);
                dump($this->session->get('filters'));
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
            'formFilter'        => $form->createView(),
            'paginate'          => $paginate,
        ]);
    }
    
}
