<?php

namespace App\Controller;

use App\Entity\Entraineur;
use App\Form\EntraineurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entraineur')]
class EntraineurController extends AbstractController
{
    #[Route('/', name: 'app_entraineur_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $entraineurs = $entityManager
            ->getRepository(Entraineur::class)
            ->findAll();

        return $this->render('entraineur/index.html.twig', [
            'entraineurs' => $entraineurs,
        ]);
    }

    #[Route('/new', name: 'app_entraineur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entraineur = new Entraineur();
        $form = $this->createForm(EntraineurType::class, $entraineur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entraineur);
            $entityManager->flush();

            return $this->redirectToRoute('app_entraineur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entraineur/new.html.twig', [
            'entraineur' => $entraineur,
            'form' => $form,
        ]);
    }

    #[Route('/{ld}', name: 'app_entraineur_show', methods: ['GET'])]
    public function show(Entraineur $entraineur): Response
    {
        return $this->render('entraineur/show.html.twig', [
            'entraineur' => $entraineur,
        ]);
    }

    #[Route('/{ld}/edit', name: 'app_entraineur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entraineur $entraineur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntraineurType::class, $entraineur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_entraineur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entraineur/edit.html.twig', [
            'entraineur' => $entraineur,
            'form' => $form,
        ]);
    }

    #[Route('/{ld}', name: 'app_entraineur_delete', methods: ['POST'])]
    public function delete(Request $request, Entraineur $entraineur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entraineur->getLd(), $request->request->get('_token'))) {
            $entityManager->remove($entraineur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_entraineur_index', [], Response::HTTP_SEE_OTHER);
    }
}
