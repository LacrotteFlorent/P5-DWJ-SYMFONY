<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Connect;

class ConnectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail', EmailType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre adresse mail'
                                ],
                'required'      => true,
            ])
            ->add('password', PasswordType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre mot de passe'
                                ],
                'required'      => true,
            ])
            ->add('submit', SubmitType::class, [
                'attr'          => ['class' => 'btn btn-primary mx-1 col-12 col-sm-auto'],
                'label'         => 'Connexion',
            ])
        ;
    }

}