<?php

namespace App\Controller;

use App\Entity\News;

use App\Form\NewsType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\DateTime;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="app_news")
     */
    public function index(): Response
    {
      
            $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->createQueryBuilder('n')
             ->join('n.lieu','lieu')
            ->getQuery()->getResult();
             $nb=count($news);
             $news1=$news;
             foreach($news1 as $news1) {
                                           
                $dd = substr($news1->getDateExpiration(),0,2);
                $mm = substr($news1->getDateExpiration(),3,2);
                $yyyy = substr($news1->getDateExpiration(),6,4);
              $dateExp=$yyyy.'-'.$mm.'-'.$dd;
              $dateA=new \DateTime('now');
              $date= $dateA->format('Y-m-d');
               if  ($dateExp <$date) {
                $nb=$nb-1;
               }

               } 
    
           return $this->render('news/index.html.twig', [
                'news' => $news,
                'nb' => $nb,
                
                
            ]);
    }

     /**
     * @Route("/newsExpire", name="app_newsExpire")
     */
    public function indexExpiré(): Response
    {
      
            $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->createQueryBuilder('n')
           ->join('n.lieu','lieu')
            ->getQuery()->getResult();
             $nb=count($news);
             $news1=$news;
             foreach($news1 as $news1) {
                                           
                $dd = substr($news1->getDateExpiration(),0,2);
                $mm = substr($news1->getDateExpiration(),3,2);
                $yyyy = substr($news1->getDateExpiration(),6,4);
              $dateExp=$yyyy.'-'.$mm.'-'.$dd;
              $dateA=new \DateTime('now');
              $date= $dateA->format('Y-m-d');
               if  ($dateExp >=$date) {
                $nb=$nb-1;
               }
               } 
    
           return $this->render('news/indexExpiré.html.twig', [
                'news' => $news,
                'nb' => $nb,
                
                
            ]);
    }

     /**
     * @Route("/AjoutNews", name="ajout_news", methods={"GET","POST"})
     */
    public function Ajout(Request $request): Response
    {
        $news = new News();
       
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $dateE=new \DateTime($request->get('dateExpiration'));
           
            $news->setDateExpiration($dateE->format('d/m/Y'));
            $date=new \DateTime('now');
            $news->setDateAjout($date->format('d/m/Y'));
            $image = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$image->guessExtension();
            try{
               $image->move(
                 $this->getParameter('image_directory'),$fileName
               );
             }catch(FileException $e){
               //...UDGCUGCUGCUCGUEGUECGUECG
             }
             $news->setImage($fileName);
             $entityManager->persist($news);
            
            $entityManager->flush();
           return $this->redirectToRoute('app_news');
            }  
        return $this->render('news/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    
    }
    /**
     * @Route("editNews/{id}", name="news_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, News $news): Response
    {
         $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dateE=new \DateTime($request->get('dateExpiration'));
           
            $news->setDateExpiration($dateE->format('d/m/Y'));
           
         
            $image = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$image->guessExtension();
            try{
               $image->move(
                 $this->getParameter('image_directory'),$fileName
               );
             }catch(FileException $e){
               //...UDGCUGCUGCUCGUEGUECGUECG
             }
             $news->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('app_news');
        }

        
        $dd = substr($news->getDateExpiration(),0,2);
        $mm = substr($news->getDateExpiration(),3,2);
        $yyyy = substr($news->getDateExpiration(),6,4);
        $date=$yyyy.'-'.$mm.'-'.$dd;
       
       
        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'date' =>$date,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("deleteNews/{id}", name="news_delete", methods={"POST","GET"})
     */
    public function delete(Request $request, News $news): Response
    {  $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository(News::class)->find($news);
       $em->remove($news);
        $em->flush();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
