<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\PropertySearch;
use App\Form\CompetitionType;
use App\Form\PropertySearchType;
use App\Repository\CompetitionRepository;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/competition')]
class Admin1Controller extends AbstractController
{
    #[Route('/', name: 'app_competition_index', methods: ['GET', 'POST'])]
    public function index(CompetitionRepository $competitionRepository, Request $request): Response
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);
        $competitions = $this->getDoctrine()->getRepository(Competition::class)->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $propertySearch->getNom();
            if ($nom != "")
                $competitions = $this->getDoctrine()->getRepository(Competition::class)->findBy(array('nom' => $nom));
            else

                $competitions = $this->getDoctrine()->getRepository(Competition::class)->findAll();
        }
        return
            $this->render('admin/competition.html.twig', ['formSearch' => $form->createView(), 'competitions' => $competitions]);
    }




    #[Route('/show/{id}', name: 'app_competition_show', methods: ['GET'])]
    public function show(Competition $competition): Response
    {
        return $this->render('admin/showcompetition.html.twig', [
            'competition' => $competition,
        ]);
    }

    #[Route('/Calendar', name: 'app_competition_calendar', methods: ['GET', 'POST'])]
    public function calendar(): Response
    {

        return $this->render('admin/Calendar.html.twig');
    }

    #[Route('/stat', name: 'stat', methods: ['GET'])]
    public function stat(EquipeRepository $equipeRepository, CompetitionRepository  $competitionRepository): Response
    {

        $equipes = $equipeRepository->CountId();
        $competitions = $competitionRepository->findAll();

        $CompetitionNames = [];
        $count = [];

        foreach ($equipes as $equipe) {
            foreach ($competitions as $competition) {
                if ($competition->getId() == $equipe["competition_id"]) {
                    array_push($CompetitionNames, $competition->getNom());
                    array_push($count, $equipe["res"]);
                }
            }
        }

        return $this->render('admin/stat.html.twig', [
            'CompetitionNames' => json_encode($CompetitionNames),
            'equipeCount' => json_encode($count),

        ]);
    }


    #[Route('/load', name: 'load', methods: ['GET', 'POST'])]
    public function load(): Response
    {

        return $this->render(
            'admin/Calendar.html.twig',
            ['competitions' => $this->getDoctrine()->getRepository(Competition::class)->findAll()]
        );
    }





    #[Route('/edit/{id}/edit', name: 'app_competition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Competition $competition, CompetitionRepository $competitionRepository): Response
    {
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competitionRepository->save($competition, true);

            return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/editcompetition.html.twig', [
            'competition' => $competition,
            'form' => $form,
        ]);
    }

    #[Route('/Delete/{id}', name: 'app_competition_delete', methods: ['POST'])]
    public function delete(Request $request, Competition $competition, CompetitionRepository $competitionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $competition->getId(), $request->request->get('_token'))) {
            $competitionRepository->remove($competition, true);
        }

        return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
    }
}
