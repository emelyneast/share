<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Theme;
use App\Form\AjoutThemeType;

class ThemeController extends AbstractController
{
    #[Route('/ajoutTheme', name: 'ajoutTheme')]
    public function ajoutTheme(Request $request): Response
    {
        $theme = new Theme();
        $form = $this->createForm(AjoutThemeType::class, $theme);

        if($request ->isMethod('POST')){
            $form ->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                
                $this->addFlash('notice','Bouton appuyer');
                $em = $this->getDoctrine()->getManager();
                $em->persist($theme);
                $em->flush();
                return $this->redirectToRoute('ajoutTheme');
            }
        }
        return $this->render('theme/ajoutTheme.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
