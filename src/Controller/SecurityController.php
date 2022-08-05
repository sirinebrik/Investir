<?php

namespace App\Controller;

use App\Entity\User;
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
    else{
    
    $user = new User();

    $form = $this->createForm(RegistrationType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        
        // Encode the new users password
        $plainPassword = $user->getPassword();
        $hash = $encoder->encodePassword($user, $plainPassword);
     
        $user->setPassword($hash);

        // Set their role
        $user->setRole('ROLE_USER');
        $user->setEtat("false");
        $date=new \DateTime('now');
        $user->setDateInscription($date->format('d/m/Y'));

        // Save
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_login');}
    }

    return $this->render('security/registration.html.twig', [
        'form' => $form->createView(),
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

            if($this->getUser()->getRole() =='ROLE_ADMIN')
                return $this->redirect($this->generateUrl('dash_admin'));
            else
                return $this->redirect($this->generateUrl('app_home'));
            
        }
}
