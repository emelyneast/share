<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use App\Form\LivredorType;
use Symfony\Component\HttpFoundation\Request;

class StaticController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function accueil(): Response
    {
        return $this->render('static/accueil.html.twig', [
            
        ]);
    }
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        if($request ->isMethod('POST')){
            $form ->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                $nom = $form->get('nom')->getData();
                $sujet = $form->get('sujet')->getData();
                $contenu = $form->get('message')->getData();
                $this->addFlash('notice','Bouton appuyer');
                $message = (new \Swift_Message($form->get('sujet')->getData()))
                ->setFrom($form->get('email')->getData())
                ->setTo('e.ansart02@gmail.com')
                //->setBody($form->get('message')->getData());
                ->setBody($this ->renderView('email/contact-email.html.twig', array('nom'=>$nom,'sujet' => $sujet, 'message' => $contenu)), 'text/html');
                $mailer -> send($message);
                return $this->redirectToRoute('contact');
            }
        }

        return $this->render('static/contact.html.twig', ['form'=>$form->createView()]);

    }

    #[Route('/apropos', name: 'apropos')]
    public function apropos(): Response
    {
        return $this->render('static/apropos.html.twig', [
            
        ]);
    }

     #[Route('/mention', name: 'mention')]
     public function mention(): Response
     {
         return $this->render('static/mention.html.twig', [
             
         ]);
     }

      #[Route('/livredor', name: 'livredor')]
      public function livredor(Request $request): Response
      {
        $form = $this->createForm(LivredorType::class);

        if($request ->isMethod('POST')){
            $form ->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                $nom = $form->get('nom')->getData();
                $nombre = $form->get('nombre')->getData();
                if($nombre < 3){
                    $this->addFlash('notice','c est pas gentil ' .$nom);
                }
                else{
                    $this->addFlash('notice','Super note merci  ' .$nom );
                }
                

            }
        }

          return $this->render('static/livredor.html.twig', ['form'=>$form->createView()
              
          ]);
      }

}
