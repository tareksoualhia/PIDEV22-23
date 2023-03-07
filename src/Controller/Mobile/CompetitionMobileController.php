<?php
namespace App\Controller\Mobile;

use App\Entity\Competition;
use App\Repository\CompetitionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/competition")
 */
class CompetitionMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(CompetitionRepository $competitionRepository): Response
    {
        $competitions = $competitionRepository->findAll();

        if ($competitions) {
            return new JsonResponse($competitions, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $competition = new Competition();

        return $this->manage($competition, $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, CompetitionRepository $competitionRepository): Response
    {
        $competition = $competitionRepository->find((int)$request->get("id"));

        if (!$competition) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($competition, $request, true);
    }

    public function manage($competition, $request, $isEdit): JsonResponse
    {   
        
        $competition->constructor(
            $request->get("nom"),
            $request->get("description"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateDebut")),
            DateTime::createFromFormat("d-m-Y", $request->get("dateFin"))
        );
        
        

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($competition);
        $entityManager->flush();

        return new JsonResponse($competition, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, CompetitionRepository $competitionRepository): JsonResponse
    {
        $competition = $competitionRepository->find((int)$request->get("id"));

        if (!$competition) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($competition);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, CompetitionRepository $competitionRepository): Response
    {
        $competitions = $competitionRepository->findAll();

        foreach ($competitions as $competition) {
            $entityManager->remove($competition);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
    
}
