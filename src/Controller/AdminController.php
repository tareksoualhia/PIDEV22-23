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

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function home(FormationRepository $formationRepository): Response
    {
        $formations=$formationRepository->findAll();
        return $this->render('admin/formation.html.twig', [
            'formations' => $formations,
        ]);
    }
   
    #[Route('/detailFormation/{id}', name: 'formationshow')]
    public function detailFormation(FormationRepository $repo,$id): Response
    {
        $result=$repo->find($id);
        return $this->render('admin/formationshow.html.twig', [
            'Formation' => $result,
        ]);
    }
    #[Route('/addFormation', name: 'formationnew')]
    public function addFormation(Request $request, ManagerRegistry $doctrine): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($formation);
            $em->flush();
            
            
             return $this->redirectToRoute('admin');
        }
       
        return $this->render('admin/formationnew.html.twig', [
             'form' => $form->createView(),
        ]);
 
    }
    #[Route('/updateformation/{id}', name: 'formationupdate')]
    public function updateformation(Request $request, ManagerRegistry $doctrine, int $id, FormationRepository $formationRepository): Response
    {
        $formation = $formationRepository->find($id);
    
        if (!$formation) {
            throw $this->createNotFoundException('The formation with id '.$id.' does not exist');
        }
    
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute('admin');
        }
    
        return $this->render('admin/formationupdate.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/removeFormation/{id}', name: 'formationremove')]
    public function deleteFormation(ManagerRegistry $doctrine, FormationRepository $repo,$id): Response
    {
        $em=$doctrine->getManager();
        $result=$repo->find($id);
        $em->remove($result);
        $em->flush(); 

        return $this->redirectToRoute('admin');
 
    }
      /**
 * @Route("stats", name="stats")
 */
public function formationsStats(FormationRepository $formationRepository): Response
{
    $formationsCountByCategory = $formationRepository->getFormationsCountByCategory();

    return $this->render('admin/stats.html.twig', [
        'formationsCountByCategory' => $formationsCountByCategory,
    ]);
}

}
