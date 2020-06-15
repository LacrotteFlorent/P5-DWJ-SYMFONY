<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
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
                'row_attr'          => ['class' => 'mb-3'],
            ])
            ->add('description', TextareaType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'description',
                        'rows'          => 3,
                ],
                'row_attr'          => ['class' => 'mt-3'],
            ])
            ->add('enabled', CheckboxType::class, [
                'attr'          => ['class' => 'custom-control-input',
                                    'id'    => 'customSwitch1',
                                    'checked'=> '',                
                                    ],
                'required'      => false,
                'row_attr'          => ['class' => 'mb-3'],
                ])
            ->add('unit', TextType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'unit',
                        'placeholder'   => 'Saisissez une unité de vente',
                ],
                'label'             => 'Unitée',
                'row_attr'          => ['class' => 'mb-3'],
            ])
            ->add('price', MoneyType::class, [
                    'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'price',
                        'placeholder'   => 'Saisissez un prix',
                ],
                'label'             => 'Prix en euros TTC',
                'row_attr'          => ['class' => 'mb-3'],
                'currency'          => false
            ])
            ->add('picture', FileType::class, [
                'attr'          => ['class' => 'form-control input-picture'],
                'label'         => 'Image',
                'row_attr'      => ['class' => 'mb-1'],
                'mapped'        => false,
            ])
            ->add('season', EntityType::class, [
                'class'         => 'App\Entity\Season',
                'attr'          => ['class' => 'form-control'],
                'choice_label'  => 'name',
                'multiple'      => false,
                'expanded'      => false,
                'label'         => 'Saison',
                'row_attr'      => ['class' => 'mb-3'],
                //'choices'       => [
                //   // 'Aucunes'       => null,
                //    'été'           => 'été',
                //    'hiver'         => 'hiver',
                //    'automne'       => 'automne',
                //    'printemps'     => 'printemps',
                //]
            ])
            ->add('category', EntityType::class, [
                'class'         => 'App\Entity\Category',
                'attr'          => ['class' => 'form-control'],
                'choice_label'  => 'title',
                'multiple'      => false,
                'expanded'      => false,
                'label'         => 'Categorie',
                'row_attr'      => ['class' => 'mb-3'],
            ])
            ->add('submit', SubmitType::class, [
                'attr'              => [
                        'class'         => 'btn btn-primary mt-3',
                ],
                'label'             => 'Créer le produit'
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
