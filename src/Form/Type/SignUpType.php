<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\SignUp;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre nom'
                                ],
                'required'      => true,
            ])
            ->add('name', TextType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre prénom'
                                ],
                'required'      => true,
            ])
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
            ->add('passwordCheck', PasswordType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre mot de passe'
                                ],
                'required'      => true,
                'mapped'        => false,
            ])
            ->add('mobileNumber', TelType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre numéro de mobile'
                                ],
                'required'      => false,
            ])
            ->add('phoneNumber', TelType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre numéro de fixe'
                                ],
                'required'      => false,
            ])
            ->add('rgpd', CheckboxType::class, [
                'mapped'            => false,
                'attr'              => [
                        'id'            => 'rgpd',
                    ]
            ])
            ->add('submit', SubmitType::class, [
                'attr'          => ['class' => 'btn btn-primary mx-1 col-12 col-sm-auto'],
                'label'         => 'Création du compte',
            ])
        ;
    }
}