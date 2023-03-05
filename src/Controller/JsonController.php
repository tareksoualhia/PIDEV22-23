<?php
namespace App\Controller;

use App\Entity\Evenment;
use App\Repository\EvenmentRepository;
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
 * @Route("/apii", name="Evenment_api_")
 */
class JsonController extends AbstractController
{
    private $entityManager;
    private $EvenmentRepository;

    public function __construct(EntityManagerInterface $entityManager, EvenmentRepository $EvenmentRepository)
    {
        $this->entityManager = $entityManager;
        $this->EvenmentRepository = $EvenmentRepository;
    }



    /**
 * @Route("/", name="index", methods={"GET"})
 */
public function index(SerializerInterface $serializer): Response
{
    $Evenments = $this->EvenmentRepository->findAll();
    $json = $serializer->serialize($Evenments, 'json', ['circular_reference_handler' => function ($object) {
        return $object->getId();
    }, 'max_depth' => 1]);

    return new Response($json, 200, [
        'Content-Type' => 'application/json'
    ]);
}

/**
 * @Route("/show/{id}", name="show", methods={"GET"})
 */
public function show(Evenment $Evenment, SerializerInterface $serializer): Response
{
    $json = $serializer->serialize($Evenment, 'json', ['circular_reference_handler' => function ($object) {
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

    $Evenment = new Evenment();
    $Evenment->setTitle($data['title']);
    $Evenment->setDateDebut(new \DateTime($data['dateDebut']));
    $Evenment->setDateFin(new \DateTime($data['dateFin']));
    $Evenment->setDescr($data['desc']);

    // Validate the entity
    $errors = $validator->validate($Evenment);
    if (count($errors) > 0) {
        return $this->json(['errors' => (string) $errors], 400);
    }

    $this->entityManager->persist($Evenment);
    $this->entityManager->flush();

    $json = $serializer->serialize($Evenment, 'json', ['circular_reference_handler' => function ($object) {
        return $object->getId();
    }, 'max_depth' => 1]);

    return new JsonResponse($json, JsonResponse::HTTP_CREATED, [], true);
}






 

}

