<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use App\Entity\Contact;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$contact = new Contact;

        $reasonChoices = [
            'Demande de renseignements'     => 'Demande de renseignements',
            'Erreur dans votre commande'    => 'Erreur dans votre commande',
            'Prise de rdv, professionel'    => 'Prise de rdv, professionel',
        ];

        $builder
            ->add('firstName', TextType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'firstName',
                        'placeholder'   => 'Nom',
                    ]
            ])
            ->add('name', TextType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'name',
                        'placeholder'   => 'Prénom',
                    ]
            ])
            ->add('email', EmailType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'email',
                        'placeholder'   => 'Mail',
                    ]
            ])
            ->add('reason', ChoiceType::class, [
                'choices'           => $reasonChoices,
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'selectReason',
                    ]
            ])
            ->add('subject', TextType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'subject',
                        'placeholder'   => 'Objet',
                    ]
            ])
            ->add('message', TextareaType::class, [
                'attr'              => [
                        'class'         => 'form-control',
                        'id'            => 'message',
                        'rows'          => 3,
                    ]
            ])
            ->add('rgpd', CheckboxType::class, [
                'mapped'            => false,
                'attr'              => [
                        'id'            => 'rgpd',
                ]
            ])
            ->add('send', SubmitType::class, [
                'attr'              => [
                        'class'         => 'btn btn-primary',
                ],
                'label'             => 'Envoyer'
            ])
        ;

        //$form = $this->createFormBuilder($contact)->getForm();
    }
}