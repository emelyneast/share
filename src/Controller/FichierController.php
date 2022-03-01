<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Inscrire;
use App\Entity\User;
use App\Entity\Fichier;
use App\Entity\Theme;
use App\Form\AjoutThemeType;

use App\Form\AjoutFichierType;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;



class FichierController extends AbstractController
{


    #[Route('/ajoutFichier', name: 'ajoutFichier')]
    public function ajoutFichier(Request $request): Response
    {
        
        $fichier = new Fichier();
        $inscrire = new Inscrire();
        $user = new User();
        $form = $this->createForm(AjoutFichierType::class, $fichier);
        $doctrine = $this->getDoctrine();
        $em = $this->getDoctrine()->getManager();
        //$fichier->setInscrire($this->getUser());
        //$em->persist($fichier);
        $this->getUser();
        
        if($request->get('id')!=null){

          $f = $doctrine->getRepository(Fichier::class)->find($request->get('id'));
          try{
            $filesystem = new Filesystem();
            if($filesystem->exists($this->getParameter('file_directory').'/'.$f->getNom())){
              $filesystem->remove([$this->getParameter('file_directory').'/'.$f->getNom()]);
            }
          }catch(IOExceptionInterface $execption){
              $this->addFlash('notice', 'Erreur');
          }
          $em->remove($f);
          $em->flush();
          return $this->redirectToRoute('ajoutFichier');
        }
        
        $fichiers = $doctrine->getRepository(Fichier::class)->findBy(array(), array('date'=>'DESC'));

        if($request->isMethod('POST')){
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()){

              //$idTheme = $form->get('theme')->getData();
              //$theme = $this->getDoctrine()->getRepository(Theme::class)->find($idTheme);
                //dump($theme);
            // Récupération du fichier
            $fichierPhysique = $fichier->getNom();

            $fichier->setDate(new \DateTime());
            $fichier->setOriginal($fichierPhysique->getClientOriginalName());
            $ext = '';
            if($fichierPhysique->guessExtension()!= null){
                $ext = $fichierPhysique->guessExtension();
            }
            $fichier->setOriginal($fichierPhysique->getClientOriginalName());
            $fichier->setExtention($ext);
            
            $fichier->setTaille($fichierPhysique->getSize());
            $fichier->setNom(md5(uniqid()));
            //$fichier->addTheme($theme);
            try{
              $fichierPhysique->move($this->getParameter('file_directory'), $fichier->getNom());
              $this->addFlash('notice', 'Fichier envoyé');
              $em = $this->getDoctrine()->getManager();
              $em->persist($fichier);
              $em->flush();
            }
            catch(FileException $e){
              $this->addFlash('notice', 'Erreur d\'envoi');
            }

            
            return $this->redirectToRoute('ajoutFichier');
          
          }
        }
                
        return $this->render('fichier/ajoutFichier.html.twig', [
            'form' => $form->createView(),
            'fichiers'=>$fichiers
        ]);
    }
    #[Route('/telechargement-fichier/{id}', name: 'telechargement-fichier', requirements:["id"=>"\d+"])]
    public function telechargementfichier(int $id): Response
    {
      $doctrine = $this->getDoctrine();
      $repoFichier = $doctrine->getRepository(Fichier::class);
      $fichier = $repoFichier ->find($id);
      if($fichier == null){
        $this->redirectToRoute('ajoutFichier');
      }
      else{
        return $this->file($this->getParameter('file_directory').'/'.$fichier->getNom(), $fichier->getOriginal());
      }
    }


}
