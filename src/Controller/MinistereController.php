<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
