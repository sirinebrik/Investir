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
        return $this->render('investisseur/index.html.twig', [
            'controller_name' => 'InvestisseurController',
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
