<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\TypeMinistere;
use App\Entity\Ministere;
use App\Entity\Investisseur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\RegistrationType;

class SecurityController extends AbstractController
{
    
    /**
     * @Route("/inscription", name="security_registration")
     */
   public function registrationFunction(Request $request, UserPasswordEncoderInterface $encoder ){
    if ($this->getUser()!=NULL && $this->getUser()->getRole()=="ROLE_ADMIN" ){
        return $this->redirectToRoute('dash_admin') ;
    }
    elseif ($this->getUser()!=NULL && $this->getUser()->getRole()=="ROLE_INVESTISSEUR" ){
        return $this->redirectToRoute('app_investisseur') ;
    }
    elseif ($this->getUser()!=NULL && $this->getUser()->getRole()=="ROLE_MINISTERE" ){
        return $this->redirectToRoute('app_ministere') ;
    }
    else{
    
    $user = new User();

    $form = $this->createForm(RegistrationType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        
        // Encode the new users password
        $plainPassword = $form->get('password')->getData();
        $hash = $encoder->encodePassword($user, $plainPassword);
     
        $user->setPassword($hash);

        // Set their role
        if ($request->get('type_Utilisateur')=="ministére"){
            $user->setRole('ROLE_MINISTERE');  
        }
        if ($request->get('type_Utilisateur')=="investisseur"){
            $user->setRole('ROLE_INVESTISSEUR');  
        }
      
        $user->setEtat("false");
        $date=new \DateTime('now');
        $user->setDateInscription($date->format('d/m/Y'));
        

        // Save
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        if ($request->get('type_Utilisateur')=="ministére"){
            $ministere = new Ministere();
            $ministere->setUtilisateur($user);
            $typesM = $this->getDoctrine()
                 ->getRepository(TypeMinistere::class)
                 ->find($request->get('typeM'));
            $ministere->setType($typesM) ;
            $em = $this->getDoctrine()->getManager();
            $em->persist($ministere);
            $em->flush();
        }
        if ($request->get('type_Utilisateur')=="investisseur"){
            $investisseur = new Investisseur();
            $investisseur->setUtilisateur($user);
            $investisseur->setNomEntreprise($request->get('nom_entreprise')) ;
            $investisseur->setPays($request->get('pays')) ;
            $em = $this->getDoctrine()->getManager();
            $em->persist($investisseur);
            $em->flush();
        }

        return $this->redirectToRoute('app_activation');}
    }
   
    $qb = $this->getDoctrine()->getRepository(TypeMinistere::class)->createQueryBuilder('o');
    $qbm = $this->getDoctrine()->getRepository(Ministere::class)->createQueryBuilder('m');
    
    $ministere=$this->getDoctrine()->getRepository(Ministere::class)->findAll(array('type.id'));
    

    $typesM = $qb->select('o')->where($qb->expr()->notIn('o.id', $this->getDoctrine()->getRepository(Ministere::class)->createQueryBuilder('m')
    ->select('pa.id')
    ->join('m.type', 'pa')
   
    ->getDQL()))->getQuery()->getResult();
    return $this->render('security/registration.html.twig', [
        'form' => $form->createView(),'typesM'=>$typesM,
    ]);
}
    /**
     * @Route("/login", name="app_login")
     */
    public function login()
    {
        if ($this->getUser()!=NULL && $this->getUser()->getRole()=="ROLE_ADMIN" ){
            return $this->redirectToRoute('dash_admin') ;
        }
        elseif ($this->getUser()!=NULL && $this->getUser()->getRole()=="ROLE_INVESTISSEUR" ){
            return $this->redirectToRoute('app_investisseur') ;
        }
        elseif ($this->getUser()!=NULL && $this->getUser()->getRole()=="ROLE_MINISTERE" ){
            return $this->redirectToRoute('app_ministere') ;
        }
        else{
        return $this->render('security/login.html.twig');}
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
        * @Route("/secure-area", name="redirection")
        */
        public function indexAction()
        {
            if($this->getUser()->getEtat() =='false')
            return $this->render('security/loginActive.html.twig');
            elseif($this->getUser()->getRole() =='ROLE_ADMIN')
                return $this->redirect($this->generateUrl('dash_admin'));
            elseif($this->getUser()->getRole() =='ROLE_INVESTISSEUR')
                return $this->redirect($this->generateUrl('app_investisseur'));
            elseif($this->getUser()->getRole() =='ROLE_MINISTERE')
                return $this->redirect($this->generateUrl('app_ministere'));
            else{
                return $this->redirect($this->generateUrl('app_home')); 
            }
            
        }

        /**
     * @Route("/msgActivation", name="app_activation")
     */
    public function msgActivation(): Response
    {
        if ($this->getUser()!=NULL && $this->getUser()->getRole()=="ROLE_ADMIN" ){
            return $this->redirectToRoute('dash_admin') ;
        }
        elseif ($this->getUser()!=NULL && $this->getUser()->getRole()=="ROLE_INVESTISSEUR" ){
            return $this->redirectToRoute('app_investisseur') ;
        }
        elseif ($this->getUser()!=NULL && $this->getUser()->getRole()=="ROLE_MINISTERE" ){
            return $this->redirectToRoute('app_ministere') ;
        }
        else{
        return $this->render('security/msgActivation.html.twig');}
    }
}
