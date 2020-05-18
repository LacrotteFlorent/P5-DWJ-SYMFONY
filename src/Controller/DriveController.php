<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    /**
     * @Route("/drive/{page}", requirements={"page" = "\d+"}, name="drive_show")
     * @param   int $page
     * @return  array render for twig
     */
    public function show($page)
    {
        $nbProductsByPage = 6;

        $repoProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repoProduct->findAllAndPaginator($page, $nbProductsByPage);

        $paginate = [
            'page'          => $page,
            'nbPages'       => ceil(count($products) / $nbProductsByPage),
            'nameRoute'     => 'drive_show',
            'paramsRoute'   => []
        ];

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

        return $this->render('drive/drive.html.twig', [
            'products'          => $products,
            'categories'        => $categories,
            'seasons'           => $seasons,
            'formFilter'        => $form->createView(),
            'paginate'          => $paginate,
        ]);
    }
    
    /**
     * @Route("/driveFilter", name="driveFilter_showByFilter")
     */
    public function showByFilter(Request $request)
    {

        $repoCategories = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repoCategories->findAll();

        $repoProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repoProduct->findAll();

        $repoSeasons = $this->getDoctrine()->getRepository(Season::class);
        $seasons = $repoSeasons->findAll();

        $filter = new Filter;
        //dd($filter);

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
        

        return $this->render('drive/drive.html.twig', [
            'products'          => $products,
            'categories'        => $categories,
            'seasons'           => $seasons,
            'formFilter'        => $form->createView(),
        ]);
    }
}
