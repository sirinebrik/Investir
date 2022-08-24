<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
        ->where('etat= true')
        ->join('o.user','user')
        ->where('user.id= :id')
        ->setParameter('id',$this->getUser()->getId())
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
        ->where('etat= false')
        ->join('o.user','user')
        ->where('user.id= :id')
        ->setParameter('id',$this->getUser()->getId())
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
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_offre');
        }
        return $this->render('offre/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   
}
