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
        return $this->render('ministere/index.html.twig', [
            'controller_name' => 'MinistereController',
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
