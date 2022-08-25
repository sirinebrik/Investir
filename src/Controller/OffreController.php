<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;

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
        ->getQuery()->getResult();
        $nb=count($offre);


        return $this->render('offre/index1.html.twig', [
          
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
            $offre->setEtat("false");
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


            return $this->redirectToRoute('app_offre');
        }
        return $this->render('offre/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   
}
