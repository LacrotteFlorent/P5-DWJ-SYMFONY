<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\ConnectType;
use App\Form\Type\SignUpType;
use App\Entity\Connect;
use App\Entity\SignUp;

class SignController extends AbstractController
{
    /**
     * @Route("/sign_in", name="signIn_showIn")
     */
    public function showIn(Request $request)
    {
        $connect = new Connect;
        $formConnect = $this->createForm(ConnectType::class, $connect)->handleRequest($request);

        if($formConnect->isSubmitted() && $formConnect->isValid()) {

            return $this->redirectToRoute('home_show');
        }

        return $this->render('sign/signIn.html.twig', [
            'formConnect'   => $formConnect->createView(),
        ]);
    }

    /**
     * @Route("/sign_up", name="signUp_showUp")
     */
    public function showUp(Request $request)
    {
        $connect = new Connect;
        $formConnect = $this->createForm(ConnectType::class, $connect)->handleRequest($request);

        if($formConnect->isSubmitted() && $formConnect->isValid()) {

            return $this->redirectToRoute('home_show');
        }

        $signUp = new SignUp;
        $formSignUp = $this->createForm(SignUpType::class, $signUp)->handleRequest($request);

        if($formSignUp->isSubmitted() && $formSignUp->isValid()) {

            return $this->redirectToRoute('home_show');
        }

        return $this->render('sign/signUp.html.twig', [
            'formConnect'   => $formConnect->createView(),
            'formSignUp'    => $formSignUp->createView(),
        ]);
    }
}
