<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/equipe')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_equipe_index', methods: ['GET'])]
    public function index(EquipeRepository $equipeRepository): Response
    {
        return $this->render('admin/equipe.html.twig', [
            'equipes' => $equipeRepository->findAll(),
        ]);
    }


    #[Route('/show/{id}', name: 'app_equipe_show', methods: ['GET'])]
    public function show(Equipe $equipe): Response
    {
        return $this->render('admin/showequipe.html.twig', [
            'equipe' => $equipe,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EquipeRepository $equipeRepository): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $equipeRepository->save($equipe, true);

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/editequipe.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    
    #[Route('/Delete/{id}', name: 'app_equipe_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe, EquipeRepository $equipeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            $equipeRepository->remove($equipe, true);
        }

        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/searchEquipeajax", name="ajaxEquipe")
     */
    public function searchajax(Request $request ,EquipeRepository $PartRepository)
    {
        $requestString=$request->get('searchValue');
        $equipe = $PartRepository->findEquipeAjax($requestString);


        return $this->render('admin/ajax.html.twig', [
            "equipes"=>$equipe,
        ]);
    }

    
}
