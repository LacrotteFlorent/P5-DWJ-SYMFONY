<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ])
            ->add('minPrice', IntegerType::class, [
                'attr'          => ['class' => 'form-control'],
            ])
            ->add('seasons', EntityType::class, [
                'class'         => 'App\Entity\Season',
                'choice_label'  => 'name',
                'multiple'      => true,
                'expanded'      => true,
            ])
            ->add('categories', EntityType::class, [
                'class'         => 'App\Entity\Category',
                'choice_label'  => 'title',
                'multiple'      => true,
                'expanded'      => true,
            ])
            ->add('changeFilter', SubmitType::class, [
                'attr'          => ['class' => 'btn btn-primary mx-1']
            ])
        ;
    }
}