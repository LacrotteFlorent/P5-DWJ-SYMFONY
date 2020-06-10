<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        

        $builder
            ->add('name', TextType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'name',
                        'placeholder'   => 'Nom',
                ],
                'label'             => 'Nom',
            ])
            ->add('description', TextareaType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'description',
                        'rows'          => 3,
                    ]
            ])
            ->add('enabled', CheckboxType::class, [
                'attr'          => ['class' => 'form-control'],
                'label'         => 'Produit en vente / inactif',
                'required'      => false,
                ])
            ->add('unit', TextType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'unit',
                        'placeholder'   => 'Saisissez une unité de vente',
                ],
                'label'             => 'Unitée',
            ])
            ->add('price', IntegerType::class, [
                    'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'price',
                        'placeholder'   => 'Saisissez un prix',
                ],
                'label'             => 'Prix',
            ])
            ->add('category', EntityType::class, [
                'class'         => 'App\Entity\Category',
                'attr'          => ['class' => 'form-control'],
                'choice_label'  => 'title',
                'multiple'      => false,
                'expanded'      => false,
                'label'         => 'Categorie',
            ])
            ->add('picture', FileType::class, [
                'attr'          => ['class' => 'form-control'],
                'label'         => 'Image',
            ])
            ->add('season', EntityType::class, [
                'class'         => 'App\Entity\Season',
                'attr'          => ['class' => 'form-control'],
                'choice_label'  => 'name',
                'multiple'      => false,
                'expanded'      => false,
                'label'         => 'Saison',
            ])
            ->add('add', SubmitType::class, [
                'attr'              => [
                        'class'         => 'btn btn-primary',
                ],
                'label'             => 'Créer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
