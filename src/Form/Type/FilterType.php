<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Filter;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice', IntegerType::class, [
                'attr'          => ['class' => 'form-control'],
                'required'      => false,
            ])
            ->add('minPrice', IntegerType::class, [
                'attr'          => ['class' => 'form-control'],
                'required'      => false,
            ])
            ->add('seasons', EntityType::class, [
                'class'         => 'App\Entity\Season',
                'choice_label'  => 'name',
                'multiple'      => true,
                'expanded'      => true,
                'required'      => false,
            ])
            ->add('categories', EntityType::class, [
                'class'         => 'App\Entity\Category',
                'choice_label'  => 'title',
                'multiple'      => true,
                'expanded'      => true,
                'required'      => false,
            ])
            ->add('search', SearchType::class, [
                'required'      => false,
                'attr'          => [
                    'class'         => 'form-control mr-sm-2',
                    'placeholder'   => 'Rechercher un produit'            
                ]
            ])
            ->add('changeFilter', SubmitType::class, [
                'attr'          => ['class' => 'btn btn-primary mx-1']
            ])
            ->add('searchBtn', SubmitType::class, [
                'attr'          => ['class' => 'btn btn-primary mx-1'],
                'label'         => 'Rechercher'
            ])
        ;
    }
}