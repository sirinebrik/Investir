<?php

namespace App\Controller;

use App\Entity\InvestirOffre;
use App\Entity\Offre;
use App\Entity\Investisseur;
use App\Entity\Ministere;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InvestirOffreController extends AbstractController
{
    /**
     * @Route("/investir/offre/{id}", name="app_investir_offre", methods={"GET","Post"})
     */
    public function investirOffre(Request $request, Offre $offre): Response
    {
        $investisseur = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->findBy(
            ['utilisateur' => $this->getUser()]
          );
          dump($investisseur);
        
      
        $investirOffre = new InvestirOffre();
        $investirOffre->setEtat("false");
        $investirOffre->setOffre($offre);
        $investirOffre->setInvestisseur($investisseur[0]);
        $date=new \DateTime('now');
        $investirOffre->setDateInvestir($date->format('d/m/Y'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($investirOffre);
        $entityManager->flush();
        return $this->redirectToRoute('demande_NonAcceptée') ;
    }
     /**
     * @Route("/investirNonAcceptee", name="demande_NonAcceptée")
     */
    public function DemandeNonAcceptée(): Response
    {
       
        $offre = $this->getDoctrine()
        ->getRepository(InvestirOffre::class)
        ->createQueryBuilder('i')
        ->andWhere('i.etat = :etat')
        ->join('i.investisseur','inv')
        ->join('i.offre','offre')
        ->join('inv.utilisateur','user')
         ->andWhere('user.id = :id')
            ->setParameters([
                'etat' => 'false',
                'id' => $this->getUser()->getId()
              ])
        ->getQuery()->getResult();
      
        $nb=count($offre);
        

           $ministere = $this->getDoctrine()
           ->getRepository(Ministere::class)
           ->createQueryBuilder('u')
           ->join('u.utilisateur','user')
           ->where('user.etat= :etat')
           ->setParameter('etat','true')
           ->join('u.type','type')
           ->getQuery()->getResult();
          
       

        return $this->render('investir_offre/demandeNonAcceptee.html.twig', [
            'offre' => $offre,
            'nb' => $nb,
            'ministere'=>$ministere,
            
        ]);
    }
     /**
     * @Route("/investirAcceptee", name="demande_Acceptée")
     */
    public function DemandeAcceptée(): Response
    {
       
        $offre = $this->getDoctrine()
        ->getRepository(InvestirOffre::class)
        ->createQueryBuilder('i')
        ->andWhere('i.etat = :etat')
        ->join('i.investisseur','inv')
        ->join('i.offre','offre')
        ->join('inv.utilisateur','user')
         ->andWhere('user.id = :id')
            ->setParameters([
                'etat' => 'true',
                'id' => $this->getUser()->getId()
              ])
        ->getQuery()->getResult();
      
        $nb=count($offre);
       
           

           $ministere = $this->getDoctrine()
           ->getRepository(Ministere::class)
           ->createQueryBuilder('u')
           ->join('u.utilisateur','user')
           ->where('user.etat= :etat')
           ->setParameter('etat','true')
           ->join('u.type','type')
           ->getQuery()->getResult();
          
       

        return $this->render('investir_offre/demandeAcceptee.html.twig', [
            'offre' => $offre,
            'nb' => $nb,
            'ministere'=>$ministere,
            
        ]);
    }

}
