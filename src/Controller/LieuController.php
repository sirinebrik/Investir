<?php

namespace App\Controller;
use App\Entity\Lieu;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu", name="app_lieu")
     */
    public function index(): Response
    {
        $lieu = $this->getDoctrine()
        ->getRepository(Lieu::class)
        ->findAll();
        return $lieu;
    }
     /**
     * @Route("/AjoutLieu", name="ajout_lieu", methods={"GET","POST"})
     */
    public function Ajout(Request $request): Response
    {
        $lieu = new Lieu();
      
        $lieu->setVille($request->get('ville'));
        $lieu->setRegion($request->get('region'));
        $lieu->setAdresse($request->get('adresse'));
       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($lieu);
        $entityManager->flush();
            
       
        return $this->redirect($_SERVER['HTTP_REFERER']);
        
       
    }

}
