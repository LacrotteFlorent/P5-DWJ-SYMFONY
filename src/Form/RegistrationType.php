<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre Nom'
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
            ->add('email', EmailType::class, [
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
            ->add('confirmPassword', PasswordType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre mot de passe'
                                ],
                'required'      => true,
            ])
            ->add('phoneNumber', TelType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre numéro de fixe'
                                ],
                'required'      => false,
            ])
            ->add('mobileNumber', TelType::class, [
                'attr'          => [
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Votre numéro de mobile'
                                ],
                'required'      => false,
                'mapped'        => false,
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
