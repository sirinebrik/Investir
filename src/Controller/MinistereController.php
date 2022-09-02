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

class MinistereController extends AbstractController
{
    /**
     * @Route("/ministere", name="app_ministere")
     */
    public function index(): Response
    {
       
            $offreA = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->createQueryBuilder('o')
            ->andWhere('o.etat = :etat')
            ->join('o.user','user')
            ->andWhere('user.id = :id')
            ->setParameters([ 'etat' => 'true', 'id' => $this->getUser()->getId()])
            ->getQuery()->getResult();
    
            $nbA=count($offreA);
            $offre1=$offreA;
            foreach($offre1 as $offre1) {
                                          
               $dd = substr($offre1->getDateExpiration(),0,2);
               $mm = substr($offre1->getDateExpiration(),3,2);
               $yyyy = substr($offre1->getDateExpiration(),6,4);
             $dateExp=$yyyy.'-'.$mm.'-'.$dd;
             $dateA=new \DateTime('now');
             $date= $dateA->format('Y-m-d');
              if  ($dateExp <$date) {
               $nbA=$nbA-1;
              }
              } 

            $offreD = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->createQueryBuilder('o')
            ->andWhere('o.etat = :etat')
            ->join('o.user','user')
            ->andWhere('user.id = :id')
            ->setParameters([ 'etat' => 'false', 'id' => $this->getUser()->getId()])
            ->getQuery()->getResult();
    
            $nbD=count($offreD);
            
            $offreE = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->createQueryBuilder('o')
            ->andWhere('o.etat = :etat')
             ->join('o.user','user')
             ->andWhere('user.id = :id')  
                ->setParameters([
                    'etat' => 'true',
                    'id' => $this->getUser()->getId(),
                    
                  ])
            ->getQuery()->getResult();
        
           $nbE=count($offreE);
           $offre2=$offreE;
           foreach($offre2 as $offre2) {
            $dd = substr($offre2->getDateExpiration(),0,2);
        $mm = substr($offre2->getDateExpiration(),3,2);
        $yyyy = substr($offre2->getDateExpiration(),6,4);
      $dateExp=$yyyy.'-'.$mm.'-'.$dd;
      $dateA=new \DateTime('now');
      $date= $dateA->format('Y-m-d');
       if  ($dateExp >= $date) {
        $nbE=$nbE-1;
       }
       } 
        
        return $this->render('ministere/index.html.twig', [
            'nbD'=> $nbD, 
            'nbA'=> $nbA,
            'nbE'=> $nbE,
       ]);
    }
     /**
     * @Route("/profileM", name="app_profileM")
     */
    public function profile(): Response
    {
        $ministere = $this->getDoctrine()
        ->getRepository(Ministere::class)
        ->createQueryBuilder('i')
        ->join('i.utilisateur','user')
        ->join('i.type','type')
        ->andWhere('user.id = :id')
        ->setParameters([
            'id' => $this->getUser()->getId(),
          
          ])
       ->getQuery()->getResult();
       
        return $this->render('ministere/profile.html.twig', [
            'ministere' => $ministere,
        ]);
    }
}
