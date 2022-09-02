<?php

namespace App\Controller;
use App\Entity\User;
use App\Service\SendMailService;
use App\Entity\TypeMinistere;
use App\Entity\Ministere;
use App\Entity\Investisseur;
use App\Entity\InvestirOffre;
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
        $userD = $this->getDoctrine()
        ->getRepository(User::class)
        ->findBy(
            ['etat' => 'false']
          );
          $nbD=count( $userD);

          $ministere = $this->getDoctrine()
        ->getRepository(Ministere::class)
        ->findAll( );
        $nbM=count( $ministere);

        $investisseur = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->findAll( );
        $nbI=count( $investisseur);

        $offreD = $this->getDoctrine()
        ->getRepository(Offre::class)
        ->findBy(
            ['etat' => 'false']
          );
        $nbOD=count( $offreD);

        $offreA = $this->getDoctrine()
        ->getRepository(Offre::class)
        ->findBy(
            ['etat' => 'true']
          );
        $nbOA=count( $offreA);

        $mesoffres= $this->getDoctrine()
        ->getRepository(Offre::class)
        ->findBy(
            ['user'=>$this->getUser()]
          );
        $nbOM=count( $mesoffres);
        $news= $this->getDoctrine()
        ->getRepository(News::class)
        ->findAll();
        $nbN=count( $news);

        $offrediscu = $this->getDoctrine()
        ->getRepository(InvestirOffre::class)
        ->createQueryBuilder('i')
        ->where('i.etat = :etat')
        ->setParameters([
            'etat' => 'false',
          
          ])
       
        ->join('i.investisseur','inv')
        ->join('i.offre','offre')
        ->join('inv.utilisateur','user')
        ->join('offre.user','user1')
      
     ->getQuery()->getResult();
      
        $nbdi=count($offrediscu);
      
        return $this->render('pages/admin/dashboard.html.twig', [
            'nbD'=> $nbD,
            'nbM'=> $nbM,
            'nbI'=> $nbI,
            'nbOD'=> $nbOD,
            'nbOA'=> $nbOA,
            'nbOM'=> $nbOM,
            'nbN'=> $nbN,
            'nbdi'=> $nbdi,
        ]);
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
      $nb=count($user);
      
    
        return $this->render('pages/admin/activerCompte.html.twig', [
            'user' => $user,
            'nb'=> $nb,
        ]);
    }

     /**
     * @Route("/find/{id}", name="find", methods={"GET","Post"})
     */
    public function find(Request $request, User $users){
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->findBy(
            ['etat' => 'false']
          );
        $user1 = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($users->getId());
          if($user1->getRole()=="ROLE_MINISTERE"){
            $role="ROLE_MINISTERE";
        $user2 = $this->getDoctrine()
    ->getRepository(Ministere::class)
    ->createQueryBuilder('u')
    ->join('u.utilisateur','user')
    ->where('user.id= :id')
    ->setParameter('id',$users->getId())
    ->join('u.type','type')
    ->getQuery()->getResult();
   
}
    else{
        $role="ROLE_INVESTISSEUR";
        $user2 = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->createQueryBuilder('u')
        ->join('u.utilisateur','user')
        ->where('user.id= :id')
        ->setParameter('id',$users->getId())
        ->getQuery()->getResult(); 
       
    }
    return $this->render('pages/admin/popupCompte.html.twig', [
        'user2' => $user2,
        'user'=>$user,
        'role'=>$role,
    ]); 
   
    }

    /**
     * @Route("/findM/{id}", name="findM", methods={"GET","Post"})
     */
    public function findMinistere(Request $request, User $users){
       
        $user = $this->getDoctrine()
        ->getRepository(Ministere::class)
        ->createQueryBuilder('u')
        ->join('u.utilisateur','user')
        ->where("user.etat= :etat")
        ->setParameter('etat', 'true')
        ->join('u.type','type')
        ->getQuery()->getResult();

        $nb=count($user);
        $user1 = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($users->getId());
        
          
        $user2 = $this->getDoctrine()
    ->getRepository(Ministere::class)
    ->createQueryBuilder('u')
    ->join('u.utilisateur','user')
    ->where('user.id= :id')
    ->setParameter('id',$users->getId())
    ->join('u.type','type')
    ->getQuery()->getResult();
   

    
    return $this->render('pages/admin/popupMinistere.html.twig', [
        'user2' => $user2,
        'user'=>$user,
        'nb'=>$nb,
        
    ]); 
   
    }

     /**
     * @Route("/findInv/{id}", name="findInv", methods={"GET","Post"})
     */
    public function findInvestisseur(Request $request, User $users){
       
        $user = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->createQueryBuilder('u')
        ->join('u.utilisateur','user')
        ->where("user.etat= :etat")
        ->setParameter('etat', 'true')
        ->getQuery()->getResult();

        $nb=count($user);
        $user1 = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($users->getId());
        
          
        $user2 = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->createQueryBuilder('u')
        ->join('u.utilisateur','user')
        ->where('user.id= :id')
        ->setParameter('id',$users->getId())
        ->getQuery()->getResult();
   

    
    return $this->render('pages/admin/popupInvestisseur.html.twig', [
        'user2' => $user2,
        'user'=>$user,
        'nb'=>$nb,
        
    ]); 
   
    }
   

    /**
     * @Route("/showM", name="showM")
    */
    public function ShowMinistere()
    {
       
        $user = $this->getDoctrine()
        ->getRepository(Ministere::class)
        ->createQueryBuilder('u')
        ->join('u.utilisateur','user')
        ->where("user.etat= :etat")
        ->setParameter('etat', 'true')
        ->join('u.type','type')
        ->getQuery()->getResult();

        $nb=count($user);
          
          
        return $this->render('pages/admin/showMinistere.html.twig', [
            'nb' => $nb,
            'user'=>$user,
           
        ]); 
        
    }

    /**
     * @Route("/showInv", name="showInv")
    */
    public function ShowInvestisseur()
    {
        
        $user = $this->getDoctrine()
        ->getRepository(Investisseur::class)
        ->createQueryBuilder('u')
        ->join('u.utilisateur','user')
        ->where("user.etat= :etat")
        ->setParameter('etat', 'true')
        ->getQuery()->getResult();

       $nb=count($user);
          
          
        return $this->render('pages/admin/showInvestisseur.html.twig', [
            'nb' => $nb,
            'user'=>$user,
           
        ]); 
        
        
    }

     /**
     * @Route("/activationU/{id}", name="activationU", methods={"GET","Post"})
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

    /**
     * @Route("/desactivationU/{id}", name="desactivationU", methods={"GET","Post"})
    */
    public function Desactivation(Request $request, User $user ): Response
    {
        
          
          $user->setEtat("false");
          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          $em->flush();
          
          if($user->getRole()=="ROLE_MINISTERE")
            return $this->redirectToRoute('showM') ;
          else
            return $this->redirectToRoute('showInv') ;
    }
     /**
     * @Route("/profileA", name="app_profileA")
     */
    public function profile(): Response
    {
        $admin = $this->getDoctrine()
        ->getRepository(User::class)
        ->createQueryBuilder('u')
        ->andWhere('u.id = :id')
        ->setParameters([
            'id' => $this->getUser()->getId(),
          
          ])
       ->getQuery()->getResult();
       
        return $this->render('pages/admin/profile.html.twig', [
            'admin' => $admin,
        ]);
    }
}