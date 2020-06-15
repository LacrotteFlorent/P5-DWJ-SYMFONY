<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Search;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType::class, [
                'required'      => false,
                'attr'          => [
                    'class'         => 'form-control',
                    'placeholder'   => 'Rechercher un produit'            
                ],
                'label'             => false,
            ])
            ->add('searchBtn', SubmitType::class, [
                'attr'          => ['class' => 'btn btn-primary'],
                'label'         => 'Rechercher'
            ])
        ;
    }
}