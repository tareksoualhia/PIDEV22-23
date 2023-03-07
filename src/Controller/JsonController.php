<?php
namespace App\Controller;

use App\Entity\Competition;
use App\Repository\CompetitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * @Route("/apii", name="Competition_api_")
 */
class JsonController extends AbstractController
{
    private $entityManager;
    private $CompetitionRepository;

    public function __construct(EntityManagerInterface $entityManager, CompetitionRepository $CompetitionRepository)
    {
        $this->entityManager = $entityManager;
        $this->CompetitionRepository = $CompetitionRepository;
    }



    /**
 * @Route("/", name="index", methods={"GET"})
 */
public function index(SerializerInterface $serializer): Response
{
    $Competitions = $this->CompetitionRepository->findAll();
    $json = $serializer->serialize($Competitions, 'json', ['circular_reference_handler' => function ($object) {
        return $object->getId();
    }, 'max_depth' => 1]);

    return new Response($json, 200, [
        'Content-Type' => 'application/json'
    ]);
}

/**
 * @Route("/show/{id}", name="show", methods={"GET"})
 */
public function show(Competition $Competition, SerializerInterface $serializer): Response
{
    $json = $serializer->serialize($Competition, 'json', ['circular_reference_handler' => function ($object) {
        return $object->getId();
    }, 'max_depth' => 1]);

    return new Response($json, 200, [
        'Content-Type' => 'application/json'
    ]);
}

/**
 * @Route("/cr", name="create", methods={"GET","POST"})
 */
public function create(Request $request, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
{
    $data = $request->query->all();

    $Competition = new Competition();
    $Competition->setNom($data['nom']);
    $Competition->setDateDebut(new \DateTime($data['dateDebut']));
    $Competition->setDateFin(new \DateTime($data['dateFin']));
    $Competition->setDescription($data['description']);
 

    // Validate the entity
    $errors = $validator->validate($Competition);
    if (count($errors) > 0) {
        return $this->json(['errors' => (string) $errors], 400);
    }

    $this->entityManager->persist($Competition);
    $this->entityManager->flush();

    $json = $serializer->serialize($Competition, 'json', ['circular_reference_handler' => function ($object) {
        return $object->getId();
    }, 'max_depth' => 1]);

    return new JsonResponse($json, JsonResponse::HTTP_CREATED, [], true);
}






 

}
