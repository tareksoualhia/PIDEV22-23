<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use App\Form\ReponseType;

use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RechercheNumero;
use App\Form\RechercheNumeroType;

class ReclamationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        $reclamations=$reclamationRepository->findAll();
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
    #[Route('/home', name: 'home')]
    public function home(): Response
    {
   
        return $this->render('home/index.html.twig');
          
    }
   /* #[Route('/home2', name: 'home2')]
    public function home2(): Response
    {
   
        return $this->render('home2/base0.html.twig');
          
    }*/




    #[Route('/secondpage', name: 'secondpage')]
    public function secondpage(): Response
    {
   
        return $this->render('secondpage/base1.html.twig');
          
    }
  


    #[Route('/addReclamation', name: 'addReclamation')]
    public function addReclamation(Request $request, ManagerRegistry $doctrine): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em=$doctrine->getManager();
            $em->persist($reclamation);
            $em->flush();
            return $this->redirectToRoute('index');
            
        }

        return $this->render('reclamation/new.html.twig', [
             'form' => $form->createView(),
        ]);
    }
    #[Route('/detailReclamation/{id}', name: 'show')]
    public function detailReclamation(ReclamationRepository $repo,$id): Response
    {
        $result=$repo->find($id);
        return $this->render('reclamation/show.html.twig', [
            'Reclamation' => $result,
        ]);
    }


    #[Route('/updatereclamation/{id}', name: 'update')]
    public function updatereclamation(Request $request, ManagerRegistry $doctrine,reclamation $reclamation, reclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(reclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($reclamation);
            $em->flush();
             return $this->redirectToRoute('index');
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/removeReclamation/{id}', name: 'remove')]
    public function deleteReclamation(ManagerRegistry $doctrine, ReclamationRepository $repo,$id): Response
    {
        $em=$doctrine->getManager();
        $result=$repo->find($id);
        $em->remove($result);
        $em->flush(); 

        return $this->redirectToRoute('index');
    }
  /* #[Route('/repondrereclamation/{id}', name: 'reponse')]
    public function RepondreReclamation(Request $request, ManagerRegistry $doctrine,Reponse $reclamation, reclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReponseType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em=$doctrine->getManager();
            $result=$repo->find($id);
           
            $reclamation->setId($result->id);
            $reclamation->setNumero($result->Numero);
            $reclamation->setNom($result->Nom);
            $reclamation->setDescription($result->Description);
            $reclamation->setType($result->Type);
            


            /*$em->persist($reclamation);
            $em->flush();*/
             //return $this->redirectToRoute('index');
        //}

        //return $this->render('reclamation/reponse.html.twig', [
            //'reclamation' => $reclamation,
            //'form' => $form->createView(),
        //]);
    //}




    #[Route('/repondreReclamation/{id}', name: 'reponse')]
    public function reponseReclamation(Request $request, ManagerRegistry $doctrine,ReclamationRepository $repo,$id): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$doctrine->getManager();
            $result=$repo->find($id);
           // $reponse->setReclamation($result);

           /* $reclamation->setId($result->getId());
            $reclamation->setNumero($result->getNumero());
            $reclamation->setEmail($result->getEmail());
            $reclamation->setNom($result->getNom());
            $reclamation->setDescription($result->getDescription());
            $reclamation->setType($result->getType());
            */
            $result->addReponse($reponse);

            //$em->remove($result);
            $em->persist($result);
            $em->persist($reponse);
              
            $em->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('reclamation/envoieR.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/removeReponse/{id}', name: 'delete')]
    public function deleteReponse(ManagerRegistry $doctrine, ReponseRepository $repo,$id): Response
    {
        $em=$doctrine->getManager();
        $result=$repo->find($id);
        $em->remove($result);
        $em->flush(); 

        return $this->redirectToRoute('index');
    }


    #[Route('/updatereponse/{id}', name: 'miseajour')]
    public function updatereponse(Request $request, ManagerRegistry $doctrine,reponse $reponse, reponseRepository $reponseRepository): Response
    {
        $form = $this->createForm(reponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $em=$doctrine->getManager();

            $em->persist($reponse);
            $em->flush();
             return $this->redirectToRoute('index');
        }

        return $this->render('reclamation/edit1.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/detailReponse/{id}', name: 'affichage')]
    public function detailReponse(ReponseRepository $repo,$id): Response
    {
        $result=$repo->find($id);
        return $this->render('reclamation/show2.html.twig', [
            'Reponse' => $result,
        ]);
    }

    #[Route('/tableUti/{num}', name: 'index2')]
    public function index2(ReclamationRepository $reclamationRepository,$num): Response
    {
        $reclamations=$reclamationRepository->findBy(["Numero" => $num]);
        return $this->render('reclamation/index2.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }


}







    


