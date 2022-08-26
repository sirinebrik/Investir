<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\Lieu;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;
use Doctrine\ORM\Mapping as ORM;


class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       

        
        $builder
            ->add('titre',TextType::class, array( 'attr'=> array( 'class'   => 'form-control')))
            ->add('description',TextareaType::class, array( 'attr'=> array( 'class'   => 'form-control textarea')))
            ->add('budget',IntegerType::class, array( 'attr'=> array( 'class'   => 'form-control','min' =>1000)))
            ->add('papiers',TextareaType::class, array( 'attr'=> array( 'class'   => 'form-control textarea')))
            ->add('delai',IntegerType::class, array( 'attr'=> array( 'class'   => 'form-control delai')))
            ->add('image',FileType::class, array( 'attr'=> array( 'class'   => 'form-control'),'data_class' => null))
          
            ->add('lieu', EntityType::class, ['class' => Lieu::class,'attr'=> array( 'class'   => 'form-control form-select'),  'placeholder' => 'Choisir le lieu',
                'choice_label' => function(Lieu $lieu) {
                    return sprintf('%s (%s %s)', $lieu->getVille(), $lieu->getRegion(), $lieu->getAdresse());
                }
            ]);
          
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
    private function getLieu() {
        $list = array();
        $lieu = $this->doctrine->getRepository('CoreBundle:Lieu')->getOrdered();
         
        foreach ($lieu as $lieu) {
            $ville = $lieu->getVille();
            foreach ($cat->getArticles() as $article) {
                $list->add($ville);
            }
        }
        return $list;
    }
}
