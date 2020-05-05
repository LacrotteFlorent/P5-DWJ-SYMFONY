<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DriveController extends AbstractController
{
    /**
     * @Route("/drive", name="drive_show")
     */
    public function show()
    {
        return $this->render('drive/drive.html.twig', [
            'controller_name' => 'DriveController',
        ]);
    }
}
