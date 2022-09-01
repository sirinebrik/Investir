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
       
      
        $investir->setEtat('true');
        $entityManager->persist( $investir);
        $entityManager->flush();
        
        return $this->redirect('/discussion');
       
    }

    /**
     * @Route("/message/{id}", name="app_message", methods={"GET"})
     */
    public function message(Request $request,Offre $offre): Response
    {
       

       $message = $this->getDoctrine()
        ->getRepository(Discussion::class)
        ->createQueryBuilder('d')
        ->join('d.investisseur','inv')
        ->join('inv.utilisateur','investisseur')
        ->andWhere('investisseur.id = :id1')
        ->join('d.offre','offre')
        ->andWhere('offre.id = :id')
        ->setParameters([
            'id' => $offre->getId(),
            'id1' => $this->getUser(),
         
          
          ])
        ->join('offre.user','user')
       
       ->getQuery()->getResult();
       
     
       $ministere = $this->getDoctrine()
       ->getRepository(Ministere::class)
       ->createQueryBuilder('u')
       ->join('u.utilisateur','user')
       ->where('user.etat= :etat')
       ->setParameter('etat','true')
       ->join('u.type','type')
       ->getQuery()->getResult();

       $offre1 = $this->getDoctrine()
       ->getRepository(Offre::class)
       ->createQueryBuilder('o')
       ->andWhere('o.id = :id')
       ->join('o.user','user')
       ->setParameters([ 'id' => $offre->getId() ])
      
      ->getQuery()->getResult();
        
        return $this->render('discussion/message.html.twig', [
            'message' => $message,
            'ministere' => $ministere,
            'offre' => $offre1,
           
        ]);
    }
  /**
     * @Route("/ajoutMessage/{id}", name="ajout_message", methods={"GET","Post"})
     */
    public function AjoutMessage(Request $request,Offre $offre): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $investisseur = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->createQueryBuilder('i')
        ->join('i.utilisateur','user')
        ->andWhere('user.id = :id')
        ->setParameters([
            'id' => $this->getUser()->getId(),
          
          ])
       ->getQuery()->getResult();
       
       $date=new \DateTime('now');
       $date->setTimezone(new \DateTimeZone("Africa/Tunis"));  
        $discussion = new Discussion();
       
        $discussion->setDateEnvoi($date->format('d/m/Y'));
        $discussion->setSend($investisseur[0]->getId());
        $discussion->setMessage($request->get('message'));
        $discussion->setHeure($date->format('G:i'));
        $discussion->setInvestisseur($investisseur[0]);
        $discussion->setOffre($offre);
       $entityManager->persist( $discussion);
        $entityManager->flush();
        
        return $this->redirect("/message/".$offre->getId());
    }
    /**
     * @Route("deleteMessage/{id}", name="deleteMessage", methods={"POST","GET"})
     */
    public function deleteMessage(Request $request, Discussion $discussion): Response
    {  $em = $this->getDoctrine()->getManager();
        $discussion = $em->getRepository(Discussion::class)->find($discussion);

        

        $em->remove($discussion);
        $em->flush();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
   
     /**
     * @Route("/messageAM/{id}/{id1}", name="app_messageAM", methods={"GET"})
     */
    public function messageAM(Request $request,Offre $offre): Response
    {
        
        $message = $this->getDoctrine()
        ->getRepository(Discussion::class)
        ->createQueryBuilder('d')
        ->join('d.investisseur','inv')
        ->andWhere('inv.id = :id1')
        ->join('d.offre','offre')
        ->andWhere('offre.id = :id')
        ->setParameters([
            'id' => $offre->getId(),
            'id1' => $request->attributes->get('id1'),
         
          
          ])
        ->join('offre.user','user')
       
       ->getQuery()->getResult();
     
     
       $investisseur = $this->getDoctrine()
       ->getRepository(Investisseur::class)
       ->createQueryBuilder('i')
       ->join('i.utilisateur','user')
       ->where('i.id= :id')
       ->setParameters([
       'id' => $request->attributes->get('id1'),
     ])
       ->getQuery()->getResult();

       $offre1 = $this->getDoctrine()
       ->getRepository(Offre::class)
       ->createQueryBuilder('o')
       ->andWhere('o.id = :id')
       ->join('o.user','user')
       ->setParameters([ 'id' => $offre->getId() ])
      ->getQuery()->getResult();
        
        return $this->render('discussion/messageAM.html.twig', [
            'message' => $message,
            'investisseur' => $investisseur[0],
            'offre'=>$offre1
           
           
        ]);
    }

      /**
     * @Route("/ajoutMessageAM/{id}/{id1}", name="ajout_messageAM", methods={"GET","Post"})
     */
    public function AjoutMessageAM(Request $request,Offre $offre): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $investisseur = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->createQueryBuilder('i')
        ->andWhere('i.id = :id')
        ->setParameters([
          'id' => $request->attributes->get('id1'),
          
          ])
       ->getQuery()->getResult();
       
       $date=new \DateTime('now');
       $date->setTimezone(new \DateTimeZone("Africa/Tunis"));  
        $discussion = new Discussion();
       
        $discussion->setDateEnvoi($date->format('d/m/Y'));
        $discussion->setSend($this->getUser()->getId());
        $discussion->setMessage($request->get('message'));
        $discussion->setHeure($date->format('G:i'));
        $discussion->setInvestisseur($investisseur[0]);
        $discussion->setOffre($offre);
       $entityManager->persist( $discussion);
        $entityManager->flush();
        
        return $this->redirect("/messageAM/".$offre->getId()."/".$investisseur[0]->getId());
    }

    
}
