<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_show")
     */
    public function show(Request $request)
    {
        $contact = new Contact;

        $reasonChoices = [
            'Demande de renseignements'     => 'info',
            'Erreur dans votre commande'    => 'error',
            'Prise de rdv, professionel'    => 'rdv',
        ];

        $form = $this->createFormBuilder($contact)
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
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() &&  $form->isValid()){
            // Ici on envois un mail -> Au client & au producteur
        }

        return $this->render('contact/contact.html.twig', [
            'controller_name'   => 'ContactController',
            'formContact'       => $form->createView(),
        ]);
    }

}
