<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Mailer\Mailer;
use App\Form\Type\ContactType;
use App\Entity\Contact;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_show")
     */
    public function show(Request $request, MailerInterface $mailer, $mailProducer)
    {
        $contact = new Contact;
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        
        if( $formContact->isSubmitted() &&  $formContact->isValid()){
            $contact = $formContact->getData();

            $emailToContact = (new TemplatedEmail())
            ->from($_ENV['MAIL_PRODUCER'])
            ->to($contact->getEmail())
            ->subject('Votre demande de contact : MangerPlusPrès.fr')
            ->htmlTemplate('emails/contactProducer.html.twig')
            ->context([
                'contact'       => $contact,
                'emailProducer' => $_ENV['MAIL_PRODUCER'],
            ]);

            $emailToProducer = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to($_ENV['MAIL_PRODUCER'])
            ->subject('Nouvelle demande de contact : MangerPlusPrès.fr')
            ->htmlTemplate('emails/contactClient.html.twig')
            ->context([
                'contact'       => $contact,
                'emailProducer' => $_ENV['MAIL_PRODUCER'],
            ]);
            
            $mailer->send($emailToContact);
            $mailer->send($emailToProducer);
        }

        return $this->render('contact/contact.html.twig', [
            'controller_name'   => 'ContactController',
            'formContact'       => $formContact->createView(),
        ]);
    }

}
