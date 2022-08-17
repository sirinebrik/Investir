<?php

namespace App\Controller;
use App\Entity\User;
use App\Service\SendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport\TransportInterface;



/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashboardAdmin", name="dash_admin")
     */
    public function index(): Response
    {
        return $this->render('pages/admin/dashboard.html.twig');
    }

    /**
     * @Route("/activerCompte", name="activer_compte")
     */
    public function ActiverCompte(): Response
    {
        $user = $this->getDoctrine()
    ->getRepository(User::class)
    ->findBy(
        ['etat' => 'false']
      );
    
        return $this->render('pages/admin/activerCompte.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/activation/{id}", name="activation", methods={"GET","Post"})
    */
    public function Activation(Request $request, User $user , SendMailService $mailer): Response
    {
        
         $mailer->send($this->getUser(),$user);
       
          $user->setEtat("true");
          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          $em->flush();
          
          
          return $this->redirectToRoute('activer_compte') ;
        
    }
}