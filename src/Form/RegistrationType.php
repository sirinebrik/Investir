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
use Symfony\Component\Form\Extension\Core\Type\TelType;

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
                'attr' => ['class' => 'form-control'],
                
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le nom complet !',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre nom complet doit comporter au moins {{ limit }} caractères !',
                        // max length allowed by Symfony for security reasons
                      
                    ]),
                ],])
            ->add('email' , TextType::class ,[
                'attr' => ['class' => 'form-control'],
                
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer l'adresse email !",
                    ]),
                    
                ],
                ])
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                
                'invalid_message' => "Vous n'avez pas entrer le même mot de passe !",
                'required' => true,
                'first_options'  => ['label' => false,'attr' => ['class' => 'form-control','placeholder' => 'Mot de passe']],
                'second_options' => ['label' => false,'attr' => ['class' => 'form-control','placeholder' => 'Répéter le mot de passe']],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe !',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères !',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ], ])
            ->add('tel',TelType::class ,[
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le numéro de téléphone !',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre numéro de téléphone doit comporter au moins {{ limit }} caractères !',
                        // max length allowed by Symfony for security reasons
                      
                    ]),
                ],
                
                ])
            ->add('adresse',TextType::class ,[
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer l'adresse'!",
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre adresse doit comporter au moins {{ limit }} caractères !',
                        // max length allowed by Symfony for security reasons
                      
                    ]),
                ],
                
                ])
          
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
