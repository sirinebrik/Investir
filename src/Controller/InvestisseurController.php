<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/profile", name="app_profile")
     */
    public function profile(): Response
    {
        return $this->render('investisseur/index.html.twig', [
            'controller_name' => 'InvestisseurController',
        ]);
    }
}
