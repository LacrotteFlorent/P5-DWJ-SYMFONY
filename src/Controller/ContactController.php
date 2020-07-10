<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\Type\ContactType;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Mailer\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_show")
     */
    public function show(Request $request, MailerInterface $mailer, $mailProducer, ValidatorInterface $validator)
    {
        $contact = new Contact;
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        
        if( $formContact->isSubmitted() &&  $formContact->isValid()){
           $contact = $formContact->getData();

           $emailToContact = (new TemplatedEmail())
           ->from($mailProducer)
           ->to($contact->getEmail())
           ->subject('Votre demande de contact : MangerPlusPrès.fr')
           ->htmlTemplate('emails/contactProducer.html.twig')
           ->context([
               'contact'       => $contact,
               'emailProducer' => $mailProducer,
           ]);

           $emailToProducer = (new TemplatedEmail())
           ->from($contact->getEmail())
           ->to($mailProducer)
           ->subject('Nouvelle demande de contact : MangerPlusPrès.fr')
           ->htmlTemplate('emails/contactClient.html.twig')
           ->context([
               'contact'       => $contact,
               'emailProducer' => $mailProducer,
           ]);
           
           $mailer->send($emailToContact);
           $mailer->send($emailToProducer);
        }

        return $this->render('contact/contact.html.twig', [
            'controller_name'   => 'ContactController',
            'formContact'       => $formContact->createView(),
        ]);
    }

    /**
     * @Route("/contact/checkForm", name="contact_checkForm")
     */
    public function checkForm(ValidatorInterface $validator)
    {
        $contact = new Contact;
        $formContact = $this->createForm(ContactType::class, $contact);
        $metaData = $validator->getMetadataFor($contact);
        $ids = [];
        $constraintsToJson = [];
        $constraintsNameToJson = [];
        foreach ($metaData->getConstrainedProperties() as $value){
            $ids[] = "#" . $formContact->getName() . "_" . $value;
            $constraints = $metaData->getPropertyMetadata($value)[0]->getConstraints();
            $constraintsToJson[] = $constraints;
            $constraintsName = [];
            foreach ($constraints as $constraint){
                $constraintClassName = explode("\\", get_class($constraint));
                $constraintsName[] = 'constraint' . array_pop($constraintClassName);
            }
            $constraintsNameToJson[] = $constraintsName;
        }
        $inputs = [];
        for ($i = 0; $i < count($ids); $i++){
            $input = [];
            $input[] = $ids[$i];
            $input[] = $constraintsNameToJson[$i];
            $input[] = $constraintsToJson[$i];
            $inputs[] = $input;
        }

        $response = new Response(json_encode($inputs));

        return $response;
    }

}
