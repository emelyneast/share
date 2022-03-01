<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use App\Entity\User;

class AjoutUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class)
            ->add('password',RepeatedType::class,[
                'type'=>PasswordType::class,
                'invalid_message'=>'Les mot de passe doivent etre pareil',
                'options'=>['attr'=>['class'=>'password-field']],
                'required'=>true,
                'first_options'=>['label'=>'Password'],
                'second_options'=>['label'=>'Repeat Password'],
            ])
            ->add('envoyer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'=> User::class,
        ]);
    }
}
