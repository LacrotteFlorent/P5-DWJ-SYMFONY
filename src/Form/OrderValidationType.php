<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OrderValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pickupDate', DateTimeType::class, [
                'attr'          => ['class' => 'input-group form-control'],
                'label'         => "Date et heure d'enlèvement",
                'row_attr'      => ['class' => 'mb-2'],
            ])
            ->add('customerMessage', TextareaType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'message',
                        'rows'          => 3,
                ],
                'label'         => 'Message au client',
                'row_attr'      => ['class' => 'mb-2'],
                'mapped'            => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr'              => [
                        'class'         => 'btn btn-success',
                ],
                'label'             => 'Valider la commande'
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
