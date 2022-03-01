<?php

namespace App\Form;
use App\Entity\Fichier;
use App\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AjoutFichierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',FileType::class, array('label'=>'Fichier à télécharger'))
            ->add('inscrire',EntityType::class, array('class'=>'App\Entity\Inscrire', "choice_label"=>function($inscrire){
                return $inscrire->getNom().' '.$inscrire->getPrenom();
            }))
            

            //->add('theme',EntityType::class, array('class'=>'App\Entity\Theme', "choice_label"=>'nom', 'mapped'=> false))
            ->add('themes',EntityType::class, array('class'=>Theme::class, "choice_label"=>'nom', 'expanded' => true , 'multiple' =>true))

            ->add('envoyer',SubmitType::class, array('label' => 'Ajouter'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fichier::class,
            ]);
    }
}
