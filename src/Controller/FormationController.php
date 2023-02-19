<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;



class FormationController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function home(FormationRepository $formationRepository): Response
    {
        $formations=$formationRepository->findAll();
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }
 
    #[Route('/addFormation', name: 'addFormation')]
    public function addFormation(Request $request, ManagerRegistry $doctrine): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($formation);
            $em->flush();
            
            
             return $this->redirectToRoute('index');
        }
       
        return $this->render('formation/new.html.twig', [
             'form' => $form->createView(),
        ]);
    }
    #[Route('/detailFormation/{id}', name: 'show')]
    public function detailFormation(FormationRepository $repo,$id): Response
    {
        $result=$repo->find($id);
        return $this->render('formation/show.html.twig', [
            'Formation' => $result,
        ]);
    }
 
 /**
     * @Route("/", name="homepage")
     */
    public function index(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findAll();
        
        return $this->render('base.html.twig', [
            'formations' => $formations,
        ]);
    }
    #[Route('/updateformation/{id}', name: 'update')]
    public function updateformation(Request $request, ManagerRegistry $doctrine,formation $formation, formationRepository $formationRepository): Response
    {
        $form = $this->createForm(formationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($formation);
            $em->flush();
             return $this->redirectToRoute('index');
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/removeFormation/{id}', name: 'remove')]
    public function deleteFormation(ManagerRegistry $doctrine, FormationRepository $repo,$id): Response
    {
        $em=$doctrine->getManager();
        $result=$repo->find($id);
        $em->remove($result);
        $em->flush(); 

        return $this->redirectToRoute('index');
    }
    /**
 * @Route("stats", name="stats")
 */
public function formationsStats(FormationRepository $formationRepository): Response
{
    $formationsCountByCategory = $formationRepository->getFormationsCountByCategory();

    return $this->render('formation/stats.html.twig', [
        'formationsCountByCategory' => $formationsCountByCategory,
    ]);
}
}
