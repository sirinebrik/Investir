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

class DiscussionController extends AbstractController
{
    /**
     * @Route("/discussion", name="app_discussion")
     */
    public function index(): Response
    {
        $offre = $this->getDoctrine()
        ->getRepository(InvestirOffre::class)
        ->createQueryBuilder('i')
        ->where('i.etat = :etat')
        ->setParameters([
            'etat' => 'false',
          
          ])
       
        ->join('i.investisseur','inv')
        ->join('i.offre','offre')
        ->join('inv.utilisateur','user')
        ->join('offre.user','user1')
      
     ->getQuery()->getResult();
      
        $nb=count($offre);
        return $this->render('discussion/index.html.twig', [
            'offre' => $offre,
            'nb' => $nb,
        ]);
    }

    /**
     * @Route("/details/{id}/{id1}", name="app_details", methods={"GET"})
     */
    public function details(Request $request): Response
    {
        $investir = $this->getDoctrine()
        ->getRepository(InvestirOffre::class)
        ->createQueryBuilder('i')
        ->join('i.investisseur','inv')
        ->andWhere('inv.id = :id')
        ->join('i.offre','offre')
      
        ->andWhere('offre.id = :id1')
        ->setParameters([
            'id' => $request->attributes->get('id'),
            'id1' => $request->attributes->get('id1'),
          
          ])
       ->getQuery()->getResult();
     
        $offre = $this->getDoctrine()
        ->getRepository(Offre::class)
        ->createQueryBuilder('o')
        ->join('o.user','user')
        ->andWhere('o.id = :id')
        ->setParameters([
            'id' =>  $request->attributes->get('id1'),
          
          ])
         ->join('o.lieu','lieu')
        ->getQuery()->getResult();

        $investisseur = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->createQueryBuilder('i')
     
        ->andWhere('i.id = :id')
        ->setParameters([
            'id' => $request->attributes->get('id'),
          
          ])
          ->join('i.utilisateur','user')
        ->getQuery()->getResult();


        $ministere = $this->getDoctrine()
        ->getRepository(Ministere::class)
        ->createQueryBuilder('u')
        ->join('u.utilisateur','user')
        ->where('user.etat= :etat')
        ->setParameter('etat','true')
        ->join('u.type','type')
        ->getQuery()->getResult();
        return $this->render('discussion/detail.html.twig', [
            'offre' => $offre,
            'investir' => $investir,
            'investisseur' => $investisseur,
            'ministere'=> $ministere,
        ]);
    }

     /**
     * @Route("/ouvreDiscussion/{id}", name="app_ouvreDiscussion", methods={"GET","Post"})
     */
    public function ouvreDiscussion(Request $request,InvestirOffre $investir): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
       
        $discussion = new Discussion();
        $discussion->setDateEnvoi('');
        $discussion->setSend('');
        $discussion->setMessage('');
        $discussion->setInvestisseur($investir->getInvestisseur());
        $discussion->setOffre($investir->getOffre());
       $entityManager->persist( $discussion);
        $entityManager->flush();

        $investir->setEtat('true');
        $entityManager->persist( $investir);
        $entityManager->flush();
        
        return $this->redirect('/discussion');
       
    }
}
