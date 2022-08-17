<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class  ,[
                'attr' => ['class' => 'form-control']])
            ->add('email' , EmailType::class ,[
                'attr' => ['class' => 'form-control']])
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'required' => true,
                'first_options'  => ['label' => false,'attr' => ['class' => 'form-control','placeholder' => 'Mot de passe']],
                'second_options' => ['label' => false,'attr' => ['class' => 'form-control','placeholder' => 'Répéter le mot de passe']],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ], ])
            ->add('tel',TextType::class ,[
                'attr' => ['class' => 'form-control']])
            ->add('adresse',TextType::class ,[
                'attr' => ['class' => 'form-control']])
          
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
