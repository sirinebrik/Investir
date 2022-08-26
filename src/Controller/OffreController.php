<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Entity\TypeMinistere;
use App\Entity\Ministere;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\DateTime;




class OffreController extends AbstractController
{
    /**
     * @Route("/offreActif", name="app_offre")
     */
    public function index(): Response
    {
        if ($this->getUser()->getRole()=="ROLE_MINISTERE"){
        $offre = $this->getDoctrine()
        ->getRepository(Offre::class)
        ->createQueryBuilder('o')
        ->andWhere('o.etat = :etat')
        ->join('o.user','user')
            ->andWhere('user.id = :id')
            ->setParameters([
                'etat' => 'true',
                'id' => $this->getUser()->getId()
              ])
          
        ->join('o.lieu','lieu')
        ->getQuery()->getResult();
    }
    if ($this->getUser()->getRole()=="ROLE_ADMIN"){
       
        $date=new \DateTime('now');
        $offre = $this->getDoctrine()
        ->getRepository(Offre::class)
        ->createQueryBuilder('o')
        ->andWhere('o.etat = :etat')
        ->andWhere('o.dateExpiration >= :dateExpiration')
        ->join('o.user','user')
       
        ->andWhere('user.role != :role')  
            ->setParameters([
                'etat' => 'true',
                'role' => 'ROLE_ADMIN',
                'dateExpiration' => $date->format('d/m/Y')
              ])
          
        ->join('o.lieu','lieu')
        ->getQuery()->getResult();
    }
        
        $nb=count($offre);



        return $this->render('offre/index.html.twig', [
            'offre' => $offre,
            'nb' => $nb,
            
        ]);
    }

    /**
     * @Route("/offreDesactif", name="app_offre1")
     */
    public function index1(): Response
    {
        if ($this->getUser()->getRole()=="ROLE_ADMIN"){
            $date=new \DateTime('now');
        $offre = $this->getDoctrine()
        ->getRepository(Offre::class)
        ->createQueryBuilder('o')
        ->andWhere('o.etat = :etat')
        ->join('o.user','user')
        ->andWhere('user.role != :role')
        ->andWhere('o.dateExpiration >= :dateExpiration')
            ->setParameters([
                'etat' => 'false',
                'role' => 'ROLE_ADMIN',
                'dateExpiration' => $date->format('d/m/Y')
              ])
          
        ->join('o.lieu','lieu')
        ->getQuery()->getResult();}
        if ($this->getUser()->getRole()=="ROLE_MINISTERE"){
            $offre = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->createQueryBuilder('o')
            ->andWhere('o.etat = :etat')
            ->join('o.user','user')
                ->andWhere('user.id = :id')
                ->setParameters([
                    'etat' => 'false',
                    'id' => $this->getUser()->getId()
                  ])
              
            ->join('o.lieu','lieu')
            ->getQuery()->getResult();}
        $nb=count($offre);


        return $this->render('offre/index1.html.twig', [
          
            'offre' => $offre,
            'nb' => $nb,
        ]);
    }
 /**
     * @Route("/offreExpire", name="expire_offre")
     */
    public function indexExpire(): Response
    {
       
        $date=new \DateTime('now');
        $offre = $this->getDoctrine()
        ->getRepository(Offre::class)
        ->createQueryBuilder('o')
        ->andWhere('o.etat = :etat')
        ->andWhere('o.dateExpiration < :dateExpiration')
        ->join('o.user','user')
       
        ->andWhere('user.role != :role')  
            ->setParameters([
                'etat' => 'true',
                'role' => 'ROLE_ADMIN',
                'dateExpiration' => $date->format('d/m/Y')
              ])
          
        ->join('o.lieu','lieu')
        ->getQuery()->getResult();

        
        $nb=count($offre);



        return $this->render('offre/indexExpiré.html.twig', [
            'offre' => $offre,
            'nb' => $nb,
            
        ]);
    }
    /**
     * @Route("/offreActifAdmin", name="app_offre_admin")
     */
    public function indexAdmin(): Response
    {
       
        $offre = $this->getDoctrine()
        ->getRepository(Offre::class)
        ->createQueryBuilder('o')
        ->andWhere('o.etat = :etat')
        ->join('o.user','user')
            ->andWhere('user.id = :id')
            ->setParameters([
                'etat' => 'true',
                'id' => $this->getUser()->getId()
              ])
          
        ->join('o.lieu','lieu')
        ->getQuery()->getResult();
   
        
        $nb=count($offre);



        return $this->render('offre/indexAdmin.html.twig', [
            'offre' => $offre,
            'nb' => $nb,
            
        ]);
    }

    
     /**
     * @Route("/AjoutOffre", name="ajout_offre", methods={"GET","POST"})
     */
    public function Ajout(Request $request): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $dateE=new \DateTime($request->get('dateExpiration'));
           
            $offre->setDateExpiration($dateE->format('d/m/Y'));
            $date=new \DateTime('now');
            $offre->setDateAjout($date->format('d/m/Y'));
            $offre->setUser($this->getUser());
            if ($this->getUser()->getRole()=="ROLE_ADMIN"){
                $offre->setEtat("true");
           }
            else{
                $offre->setEtat("false");
            }
            $image = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$image->guessExtension();
            try{
               $image->move(
                 $this->getParameter('image_directory'),$fileName
               );
             }catch(FileException $e){
               //...UDGCUGCUGCUCGUEGUECGUECG
             }
             $offre->setImage($fileName);
            $entityManager->persist($offre);
            $entityManager->flush();

            if ($this->getUser()->getRole()=="ROLE_ADMIN"){return $this->redirectToRoute('app_offre_admin');}
           else{ return $this->redirectToRoute('app_offre1');}
        }
        return $this->render('offre/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("edit/{id}", name="offre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Offre $offre): Response
    {
         $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dateE=new \DateTime($request->get('dateExpiration'));
           
            $offre->setDateExpiration($dateE->format('d/m/Y'));
            $date=new \DateTime('now');
            $offre->setDateAjout($date->format('d/m/Y'));
            $image = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$image->guessExtension();
            try{
               $image->move(
                 $this->getParameter('image_directory'),$fileName
               );
             }catch(FileException $e){
               //...UDGCUGCUGCUCGUEGUECGUECG
             }
             $offre->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();
            if ($this->getUser()->getRole()=="ROLE_ADMIN"){return $this->redirectToRoute('app_offre_admin');}
           else{ return $this->redirectToRoute('app_offre');}
        }

        
        $dd = substr($offre->getDateExpiration(),0,2);
        $mm = substr($offre->getDateExpiration(),3,2);
        $yyyy = substr($offre->getDateExpiration(),6,4);
        $date=$yyyy.'-'.$mm.'-'.$dd;
       
       
        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'date' =>$date,
            'form' => $form->createView(),
        ]);
    }
/**
     * @Route("delete/{id}", name="offre_delete", methods={"POST","GET"})
     */
    public function delete(Request $request, Offre $offre): Response
    {  $em = $this->getDoctrine()->getManager();
        $offre = $em->getRepository(Offre::class)->find($offre);

        

        $em->remove($offre);
        $em->flush();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
   
     /**
     * @Route("/activation/{id}", name="activation", methods={"GET","Post"})
    */
    public function ActivationOffre(Request $request, Offre $offre ): Response
    {
          $offre->setEtat("true");
          $em = $this->getDoctrine()->getManager();
          $em->persist($offre);
          $em->flush();
          
          
          return $this->redirect($_SERVER['HTTP_REFERER']);
        
    }
     /**
     * @Route("/desactivation/{id}", name="desactivation", methods={"GET","Post"})
    */
    public function DesactivationOffre(Request $request, Offre $offre ): Response
    {
          $offre->setEtat("false");
          $em = $this->getDoctrine()->getManager();
          $em->persist($offre);
          $em->flush();
          
          
          return $this->redirect($_SERVER['HTTP_REFERER']);
        
    }

}

