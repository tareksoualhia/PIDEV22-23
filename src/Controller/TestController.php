<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenment;
use App\Form\EvenmentType;
use App\Repository\EvenmentRepository;
use Symfony\Component\HttpFoundation\Request;


#[Route('/eve')]
class TestController extends AbstractController
{
    
    #[Route('/', name: 'app_evenment_index', methods: ['GET'])]
    public function index(EvenmentRepository $evenmentRepository): Response
    {
        return $this->render('admin/admin.html.twig', [
            'evenments' => $evenmentRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_evenment_show', methods: ['GET'])]
    public function show(Evenment $evenment): Response
    {
        return $this->render('admin/show.html.twig', [
            'evenment' => $evenment,
        ]);
    }
  
    #[Route('evenment/{id}/edit', name: 'app_evenment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenment $evenment, EvenmentRepository $evenmentRepository): Response
    {
        $form = $this->createForm(EvenmentType::class, $evenment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenmentRepository->save($evenment, true);

            return $this->redirectToRoute('app_evenment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/edit.html.twig', [
            'evenment' => $evenment,
            'form' => $form,
        ]);
    }
    /**
     *
     * @Route("/search",name="search")
     */
    public function search(EvenmentRepository $repository,Request $request){
        $data=$request->get('search');
        $evenment=$repository->findByitle($data);
        return $this->render('admin/admin.html.twig',['evenment'=>$evenment]);
    }
    
    
}