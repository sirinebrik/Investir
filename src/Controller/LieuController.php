<?php

namespace App\Controller;
use App\Entity\Lieu;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
