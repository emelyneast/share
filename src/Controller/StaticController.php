<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use App\Form\LivredorType;
use App\Form\InscrireType;
use App\Form\AjoutUserType;
use App\Form\InscriptionType;
use App\Form\FichierType;
use App\Form\AjoutPhotosType;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Entity\Fichier;
use App\Entity\User;
use App\Entity\Inscrire;
use App\Entity\Livredor;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class StaticController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function accueil(): Response
    {
        return $this->render('static/accueil.html.twig', [
            
        ]);
    }

    #[Route('/partage', name: 'partage')]
    public function partage(Request $request): Response
    {

            $doctrine = $this->getDoctrine();
            $em = $this->getDoctrine()->getManager();
    
            $repoUser = $this->getDoctrine()->getRepository(User::class);
            $users = $repoUser->findBy(array(), array('email'=>'ASC'));
            if($request->get('id')!=null){
    
                $user = $doctrine->getRepository(User::class)->find($request->get('id'));
               
                $em->flush();
                return $this->redirectToRoute('partage');
              }
        
            
        return $this->render('static/partage.html.twig', ['users'=>$users
            
        ]);
    }

    
    #[Route('/ajoutUser', name: 'ajoutUser')]
    public function ajoutUser(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User();
        $form = $this->createForm(AjoutUserType::class, $user);
    
        
        if($request ->isMethod('POST')){
            $form ->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                $user->setRoles(array('ROLE_USER'));
                
                $user->setPassword($passwordHasher->hashPassword($user,$user->getPassword()));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice','Inscription réussi');
                return $this->redirectToRoute('app_login');
            }
            
        }

        return $this->render('static/ajoutUser.html.twig', ['form'=>$form->createView()]);

    }



    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        if($request ->isMethod('POST')){
            $form ->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                
                $this->addFlash('notice','Bouton appuyer par ' .$contact->getNom());
                $message = (new \Swift_Message($contact->getSujet()))
                ->setFrom($contact->getEmail())
                ->setTo('e.ansart02@gmail.com')
                //->setBody($form->get('message')->getData());
                ->setBody($this ->renderView('email/contact-email.html.twig', array('nom'=>$contact->getNom(),'sujet' => $contact->getSujet(), 'message' => $contact->getMessage())), 'text/html');
                $mailer -> send($message);

                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
                return $this->redirectToRoute('contact');
            }
        }
        

        return $this->render('static/contact.html.twig', ['form'=>$form->createView()]);

    }

    #[Route('/modifC/{id}', name: 'modifC', requirements:["id"=>"\d+"])]
    public function modifC(Request $request, int $id): Response
    {
        $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);
        //$inscrire = new Inscrire();
        $form = $this->createForm(ContactType::class, $contact);
        
            if($request ->isMethod('POST')){
                $form ->handleRequest($request);
                if($form->isSubmitted()&&$form->isValid()){
                    
                    $this->addFlash('notice','Bouton appuyer');
                    
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($contact);
                    $em->flush();
                    return $this->redirectToRoute('liste');
                }
                
            }
           
        return $this->render('static/modifC.html.twig', ['form'=>$form->createView()
            
        ]);
    }




   #[Route('/inscrire', name: 'inscrire')]
    public function inscrire(Request $request): Response
    {

        $inscrire = new Inscrire();
        $form = $this->createForm(InscrireType::class, $inscrire);
        $inscrire->setDateN(new \DateTime());
        $inscrire->setDateIn(new \DateTime());
        $doctrine = $this->getDoctrine();
        $em = $this->getDoctrine()->getManager();
        


        if($request ->isMethod('POST')){
            $form ->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                
                $this->addFlash('notice','Bouton appuyer');
                $inscrire->setDateIn(new \DateTime);
                $em = $this->getDoctrine()->getManager();
                $em->persist($inscrire);
                $em->flush();
                return $this->redirectToRoute('inscrire');
            }
            
        }
        if($request->get('id')!=null){

            $inscrire = $doctrine->getRepository(Inscrire::class)->find($request->get('id'));
           
            $em->remove($inscrire);
            $em->flush();
            return $this->redirectToRoute('inscrire');
          }

        $repoInscrire = $this->getDoctrine()->getRepository(Inscrire::class);
        $inscrires = $repoInscrire->findBy(array(), array('nom'=>'ASC'));

        return $this->render('static/inscrire.html.twig', ['form'=>$form->createView(),
                                                            'inscrires'=>$inscrires]);

    }

    #[Route('/modifU/{id}', name: 'modifU', requirements:["id"=>"\d+"])]
    public function modifU(Request $request, int $id): Response
    {
        $inscrire = $this->getDoctrine()->getRepository(Inscrire::class)->find($id);
        //$inscrire = new Inscrire();
        $form = $this->createForm(InscrireType::class, $inscrire);
        
            if($request ->isMethod('POST')){
                $form ->handleRequest($request);
                if($form->isSubmitted()&&$form->isValid()){
                    
                    $this->addFlash('notice','Bouton appuyer');
                    
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($inscrire);
                    $em->flush();
                    return $this->redirectToRoute('inscrire');
                }
                
            }
        
        return $this->render('static/modifU.html.twig', ['form'=>$form->createView()
            
        ]);
    }

     /*#[Route('/contact', name: 'contact')]
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
 
     }*/

    #[Route('/apropos', name: 'apropos')]
    public function apropos(): Response
    {
        return $this->render('static/apropos.html.twig', [
            
        ]);
    }

    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $inscrire = new Inscrire();
        $user = new User();
        $user->setInscrire($inscrire);
        $inscrire->setUser($user);
        $form = $this->createForm(InscriptionType::class,$inscrire);
        
        if($request ->isMethod('POST')){
            $form ->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                $user->setEmail($form->get('email')->getData());

                $user->setPassword($passwordHasher->hashPassword($user,$form->get('password')->getData()));
                $user->setRoles(array('ROLE_USER'));
                $this->addFlash('notice','Bouton appuyer');
                $inscrire->setDateIn(new \DateTime);
                $em = $this->getDoctrine()->getManager();
                $em->persist($inscrire);
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('inscription');
            }
            
        }

        return $this->render('static/inscription.html.twig', ['form'=>$form->createView()
            
        ]);
    }

    #[Route('/listeU', name: 'listeU')]
    public function listeU(): Response
    {
        return $this->render('static/listeU.html.twig', [
            
        ]);
    }
   

     #[Route('/ajoutPhoto/{id}', name: 'ajoutPhoto', requirements:["id"=>"\d+"])]
     public function ajoutPhoto(Request $request, int $id): Response
     {

        $inscrire = new Inscrire();
        $form = $this->createForm(AjoutPhotosType::class, $inscrire);

        $inscrire = $this->getDoctrine()->getRepository(inscrire::class)->find($id);
        
            if($request ->isMethod('POST')){
                $form ->handleRequest($request);
                if($form->isSubmitted()&&$form->isValid()){
                    
                    $this->addFlash('notice','Bouton appuyer');
                    
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($inscrire);
                    $em->flush();
                    return $this->redirectToRoute('profil');
                }
                
            }
        

         return $this->render('static/ajoutPhoto.html.twig', ['form'=>$form->createView()
             
         ]);
     }


     #[Route('/profil', name: 'profil')]
     public function profil(Request $request): Response
     {
        

         return $this->render('static/profil.html.twig', [
             
         ]);
     }
 

    #[Route('/liste', name: 'liste')]
    public function liste(Request $request): Response
    {
        $doctrine = $this->getDoctrine();
        $em = $this->getDoctrine()->getManager();

        $repoContact = $this->getDoctrine()->getRepository(Contact::class);
        $contacts = $repoContact->findBy(array(), array('nom'=>'ASC'));
        if($request->get('id')!=null){

            $contact = $doctrine->getRepository(Contact::class)->find($request->get('id'));
           
            $em->remove($contact);
            $em->flush();
            return $this->redirectToRoute('liste');
          }
    
        return $this->render('static/liste.html.twig', ['contacts'=>$contacts
            
        ]);
    }

    #[Route('/listelivre', name: 'listelivre')]
    public function listelivre(Request $request): Response
    {
        $repoLivredor = $this->getDoctrine()->getRepository(livredor::class);
        $livredors = $repoLivredor->findBy(array(), array('nom'=>'ASC'));

        $doctrine = $this->getDoctrine();
        $em = $this->getDoctrine()->getManager();

        
        if($request->get('id')!=null){

            $livredor = $doctrine->getRepository(Livredor::class)->find($request->get('id'));
           
            $em->remove($livredor);
            $em->flush();
            return $this->redirectToRoute('listelivre');
          }
    
        return $this->render('static/listelivre.html.twig', ['livredors'=>$livredors
            
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
        $livredor = new livredor();
        $form = $this->createForm(LivredorType::class, $livredor);
        $nombre = $form->get('nombre')->getData();
        if($request ->isMethod('POST')){
            $form ->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
            if($nombre <3){
    
                $this->addFlash('notice','c\'est pas gentil ', $livredor->getNom());
            }else{
                $this->addFlash('notice','merci pour la note ', $livredor->getNom());
            }
                $em = $this->getDoctrine()->getManager();
                $em->persist($livredor);
                $em->flush();
                return $this->redirectToRoute('livredor');

            }
        }

          return $this->render('static/livredor.html.twig', ['form'=>$form->createView()
              
          ]);
      }


      #[Route('/modifL/{id}', name: 'modifL', requirements:["id"=>"\d+"])]
    public function modifL(Request $request, int $id): Response
    {
        $livredor = $this->getDoctrine()->getRepository(Livredor::class)->find($id);
        
        $form = $this->createForm(LivredorType::class, $livredor);
        $nombre = $form->get('nombre')->getData();
        if($request ->isMethod('POST')){
            $form ->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
            if($nombre <3){
    
                $this->addFlash('notice','c\'est pas gentil ', $livredor->getNom());
            }else{
                $this->addFlash('notice','merci pour la note ', $livredor->getNom());
            }
                $em = $this->getDoctrine()->getManager();
                $em->persist($livredor);
                $em->flush();
                return $this->redirectToRoute('listelivre');

            }
        }

          return $this->render('static/modifL.html.twig', ['form'=>$form->createView()
              
          ]);
      }

}
