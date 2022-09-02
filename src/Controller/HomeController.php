<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SendMailServiceHome;
use App\Entity\TypeMinistere;
use App\Entity\Ministere;
use App\Entity\Investisseur;
use App\Entity\Offre;
use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport\TransportInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->findBy(
            ['role' => 'ROLE_ADMIN']
          );

          $offre = $this->getDoctrine()
        ->getRepository(Offre::class)
        ->createQueryBuilder('o')
        ->andWhere('o.etat = :etat')
        ->setParameters([ 'etat' => 'true' ])
         ->join('o.lieu','lieu')
       ->getQuery()->getResult();

        $nb=count($offre);
         $offre1=$offre;

         foreach($offre1 as $offre1) {
            $dd = substr($offre1->getDateExpiration(),0,2);
            $mm = substr($offre1->getDateExpiration(),3,2);
            $yyyy = substr($offre1->getDateExpiration(),6,4);
          $dateExp=$yyyy.'-'.$mm.'-'.$dd;
          $dateA=new \DateTime('now');
          $date= $dateA->format('Y-m-d');
           if  ($dateExp <$date) {
            $nb=$nb-1;
           }
           } 

           $ministere = $this->getDoctrine()
           ->getRepository(Ministere::class)
           ->createQueryBuilder('u')
           ->join('u.utilisateur','user')
           ->where('user.etat= :etat')
           ->setParameter('etat','true')
           ->join('u.type','type')
           ->getQuery()->getResult();

           $news = $this->getDoctrine()
           ->getRepository(News::class)
           ->createQueryBuilder('n')
            ->join('n.lieu','lieu')
           ->getQuery()->getResult();
            $nbn=count($news);
            $news1=$news;
            foreach($news1 as $news1) {
                                          
               $dd = substr($news1->getDateExpiration(),0,2);
               $mm = substr($news1->getDateExpiration(),3,2);
               $yyyy = substr($news1->getDateExpiration(),6,4);
             $dateExp=$yyyy.'-'.$mm.'-'.$dd;
             $dateA=new \DateTime('now');
             $date= $dateA->format('Y-m-d');
              if  ($dateExp <$date) {
               $nbn=$nbn-1;
              }

              } 
         
        return $this->render('pages/home/index.html.twig', [
            'user' => $user[0],
            'offre' => $offre,
            'nb' => $nb,
            'nbn' => $nbn,
            'news' => $news,
            'ministere'=>$ministere,
        ]);
    }

     /**
     * @Route("/sendEmail", name="sendEmail", methods={"GET","Post"})
    */
    public function sendEmail(Request $request, SendMailServiceHome $mailer): Response
    {
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->findBy(
            ['role' => 'ROLE_ADMIN']
          );
        $message=$request->get('message');
        if($this->getUser()==null){
            $email=$request->get('email'); 
            $name=$request->get('name'); 
        }
        else{
            $email=$this->getUser()->getEmail();
            $name=$this->getUser()->getUsername();
        }
         $mailer->send($email,$user[0]->getEmail(),$message,$name);
       
          
          return $this->redirectToRoute('home') ;
        
    }
}
