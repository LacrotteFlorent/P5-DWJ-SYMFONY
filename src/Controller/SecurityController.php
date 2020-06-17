<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Connect;
use App\Form\RegistrationType;
use App\Form\Type\ConnectType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ManagerRegistry $managerRegistry, UserPasswordEncoderInterface $encoder)
    {
        $user = new User;
        $user->setRoles('ROLE_USER');

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            if($form['mobileNumber']->getData()){
                $user->setPhoneNumber($form['mobileNumber']->getData());
            }

            $manager = $managerRegistry->getManager();
            $manager->persist($user);
            $manager->flush();
        }
        else{
            return $this->render('security/registration.html.twig', [
            'form'      => $form->createView(),
            ]);
        }

        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(Request $request)
    {
        return $this->render('security/connect.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        
    }

    /**
     * @Route("/deconnexion_page", name="security_logoutShow")
     */
    public function logoutShow()
    {
        return $this->render('security/disconnect.html.twig');
    }
}
