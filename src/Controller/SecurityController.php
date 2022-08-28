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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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
    $err1="0";
    $err2=$request->get('typeM');
    $err3=$request->get('pays');
    $err4=$request->get('nom_entreprise');
    if ($form->isSubmitted()){
        $err1=$request->get('type_Utilisateur');
    if ($form->isValid() && $err1!= null && (( $err2!= null) || ($err3!= null && $err4!= null))) {
      
      
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
     }}
    
   
    $qb = $this->getDoctrine()->getRepository(TypeMinistere::class)->createQueryBuilder('o');
    $qbm=$this->getDoctrine()->getRepository(Ministere::class)->createQueryBuilder('m')
    ->select('ty.id')
    ->join('m.type', 'ty')
    ->join('m.utilisateur','user')
    ->where($qb->expr()->in('user.etat', ['true']));
   
   
    $typesM = $qb->select('o')->where($qb->expr()->notIn('o.id', $qbm->getDQL()))->getQuery()->getResult();
    return $this->render('security/registration.html.twig', [
        'form' => $form->createView(),'typesM'=>$typesM,'err1'=>$err1,'err2'=>$err2,'err3'=>$err3,'err4'=>$err4,
    ]);
}
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $error1="";
        $error2="";
       
        if ($error){
         
           $qr=$this->getDoctrine()->getRepository(User::class)->findBy(['email' => $authenticationUtils->getLastUsername()]);
               $count=count($qr);
              
                if(!$count){
            $error1="L’adresse e-mail que vous avez saisie n’est pas associée à un compte. Trouvez votre compte et connectez-vous!";
                }
                else {
                    $error2="Le mot de passe entré est incorrect!"; 
                }
    
    }
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
            return $this->render('security/login.html.twig', [
               
                'error' => $error,
                'error1' => $error1,
                'error2' => $error2,

            ]);}
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
            if($this->getUser()->getEtat() =='false'){
                $this->get('security.token_storage')->setToken(null);

            return $this->redirect($this->generateUrl('loginActive'));}
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

     /**
     * @Route("/loginActive", name="loginActive")
     */
    public function loginActive()
    {
        return $this->render('security/loginActive.html.twig');
    }
}
