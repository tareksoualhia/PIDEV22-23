<?php
namespace App\Controller\Mobile;

use App\Entity\Equipe;
use App\Repository\EquipeRepository;
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
 * @Route("/mobile/equipe")
 */
class EquipeMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(EquipeRepository $equipeRepository): Response
    {
        $equipes = $equipeRepository->findAll();

        if ($equipes) {
            return new JsonResponse($equipes, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, CompetitionRepository $competitionRepository): JsonResponse
    {
        $equipe = new Equipe();

        return $this->manage($equipe, $competitionRepository,  $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, EquipeRepository $equipeRepository, CompetitionRepository $competitionRepository): Response
    {
        $equipe = $equipeRepository->find((int)$request->get("id"));

        if (!$equipe) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($equipe, $competitionRepository, $request, true);
    }

    public function manage($equipe, $competitionRepository, $request, $isEdit): JsonResponse
    {   
        $competition = $competitionRepository->find((int)$request->get("competition"));
        if (!$competition) {
            return new JsonResponse("competition with id " . (int)$request->get("competition") . " does not exist", 203);
        }
        
        
        $equipe->constructor(
            $competition,
            $request->get("nom"),
            $request->get("description"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateCreation"))
        );
        
        

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($equipe);
        $entityManager->flush();

        return new JsonResponse($equipe, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, EquipeRepository $equipeRepository): JsonResponse
    {
        $equipe = $equipeRepository->find((int)$request->get("id"));

        if (!$equipe) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($equipe);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, EquipeRepository $equipeRepository): Response
    {
        $equipes = $equipeRepository->findAll();

        foreach ($equipes as $equipe) {
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
    
}
