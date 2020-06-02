<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\AddCart;

class AddCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productId', HiddenType::class)

            ->add('productPage', HiddenType::class)

            ->add('productNumber', ChoiceType::class, [
                'choices'  => [
                    '1'     => 1,
                    '2'     => 2,
                    '3'     => 3,
                    '4'     => 4,
                    '5'     => 5,
                    '6'     => 6,
                ],
            ])
        ;
    }
}