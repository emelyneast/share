<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscrireController extends AbstractController
{
    #[Route('/inscrire', name: 'inscrire')]
    public function index(): Response
    {
        return $this->render('inscrire/index.html.twig', [
            'controller_name' => 'InscrireController',
        ]);
    }

     #[Route('/listeU', name: 'listeU')]
     public function listeU(): Response
     {


         return $this->render('inscrire/listeU.html.twig', [
             
         ]);
     }
}
