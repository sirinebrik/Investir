<?php

namespace App\Controller;

use App\Entity\InvestirOffre;
use App\Entity\Offre;
use App\Entity\Investisseur;
use App\Entity\Ministere;
use App\Entity\Discussion;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InvestisseurController extends AbstractController
{
    /**
     * @Route("/investisseur", name="app_investisseur")
     */
    public function index(): Response
    {
        $investisseur = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->createQueryBuilder('i')
        ->join('i.utilisateur','user')
        ->andWhere('user.id = :id')
        ->setParameters([
            'id' => $this->getUser()->getId(),
          
          ])
       ->getQuery()->getResult();

        $investirD = $this->getDoctrine()
        ->getRepository(InvestirOffre::class)
        ->findBy(
            ['etat' => 'false','investisseur'=>$investisseur[0] ]
          );
          $nbD=count( $investirD);

          $investirA = $this->getDoctrine()
          ->getRepository(InvestirOffre::class)
          ->findBy(
              ['etat' => 'true','investisseur'=>$investisseur[0] ]
            );
            $nbA=count( $investirA);

            $offre = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->createQueryBuilder('o')
            ->andWhere('o.etat = :etat')
            ->setParameters([ 'etat' => 'true' ])
            ->getQuery()->getResult();
    
            $nb=count($offre);
             $offre1=$offre;
    
             foreach($offre1 as $offre1) {
                $dd = substr($offre1->getDateExpiration(),0,2);
                $mm = substr($offre1->getDateExpiration(),3,2);
                $yyyy = substr($offre1->getDateExpiration(),6,4);
              $dateExp=$yyyy.'-'.$mm.'-'.$dd;
              $dateA=new \DateTime('now');
              $date= $dateA->format('Y-m-d');
               if  ($dateExp <$date) {
                $nb=$nb-1;
               }
               } 
    

        
        return $this->render('investisseur/index.html.twig', [
            'nbD'=> $nbD, 
            'nbA'=> $nbA,
            'nb'=> $nb,
           
        ]);
    }

     /**
     * @Route("/profileInv", name="app_profileInv")
     */
    public function profile(): Response
    {
        $investisseur = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->createQueryBuilder('i')
        ->join('i.utilisateur','user')
        ->andWhere('user.id = :id')
        ->setParameters([
            'id' => $this->getUser()->getId(),
          
          ])
       ->getQuery()->getResult();
       
        return $this->render('investisseur/profile.html.twig', [
            'investisseur' => $investisseur,
        ]);
    }
}
