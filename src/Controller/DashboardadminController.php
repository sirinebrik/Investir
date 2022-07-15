<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardadminController extends AbstractController
{
    /**
     * @Route("/dashboardAdmin", name="dash_admin")
     */
    public function index(): Response
    {
        return $this->render('pages/admin/dashboard.html.twig');
    }
}