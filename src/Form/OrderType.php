<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pickupDate', DateType::class, [
                'attr'          => ['class' => 'form-control'],
                'label'         => false,
                'row_attr'      => ['class' => 'mb-1'],
            ])
            ->add('pickupTime', TimeType::class, [
                'attr'          => ['class' => 'form-control'],
                'label'         => false,
                'row_attr'      => ['class' => 'mb-1'],
                'mapped'        => false
            ])
            ->add('submit', SubmitType::class, [
                'attr'              => [
                        'class'         => 'btn btn-primary',
                ],
                'label'             => 'Finaliser ma commande'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
