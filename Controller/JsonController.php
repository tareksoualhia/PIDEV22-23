<?php
namespace App\Controller;

use App\Entity\Reclamation;

use App\Repository\ReclamationRepository;
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
use App\Form\ReclamationType;
use Symfony\Component\Serializer\Serializer;
use App\Form\ReclamationFilterType;
use DateTime;



/**
 * @Route("/apii", name="reclamation_api_")
 */
class JsonController extends AbstractController
{
    private $entityManager;
    private $ReclamationRepository;

    public function __construct(EntityManagerInterface $entityManager, ReclamationRepository $ReclamationRepository)
    {
        $this->entityManager = $entityManager;
        $this->ReclamationRepository = $ReclamationRepository;
    }



    /**
 * @Route("/index", name="index", methods={"GET"})
 */
public function index(SerializerInterface $serializer): Response
{
    $Reclamations = $this->ReclamationRepository->findAll();
    $json = $serializer->serialize($Reclamations, 'json', ['circular_reference_handler' => function ($object) {
        return $object->getId();
    }, 'max_depth' => 1]);

    return new Response($json, 200, [
        'Content-Type' => 'application/json'
    ]);
}

/**
 * @Route("/show/{id}", name="show", methods={"GET"})
 */
public function show(Reclamation $Reclamation, SerializerInterface $serializer): Response
{
    $json = $serializer->serialize($Reclamation, 'json', ['circular_reference_handler' => function ($object) {
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

    $reclamation = new Reclamation();
    $reclamation->setType($data['type']);
    $reclamation->setDescription($data['description']);
   // $reclamation->setDate($data['date']);
   $reclamation->setDate(new \DateTime($data['date']));

    
    // Validate the entity
    $errors = $validator->validate($reclamation);
    if (count($errors) > 0) {
        return $this->json(['errors' => (string) $errors], 400);
    }

    $this->entityManager->persist($reclamation);
    $this->entityManager->flush();

    $json = $serializer->serialize($reclamation, 'json', ['circular_reference_handler' => function ($object) {
        return $object->getId();
    }, 'max_depth' => 1]);

    return new JsonResponse($json, JsonResponse::HTTP_CREATED, [], true);
}
 /**
     * @Route("/update/{id}", name="update_reclamation", methods={"GET", "POST"})
    
     */
    public function modifierReclamationAction(Request $request, SerializerInterface $serializer) {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getManager()
                        ->getRepository(Reclamation::class)
                        ->find($request->get("id"));

        $reclamation->setType($request->get("type"));
        $reclamation->setDescription($request->get("description"));
        $reclamation->setDate(new \DateTime($request->get('date')));
        
      

        $em->persist($reclamation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse("Reclamation a ete modifiee avec success.");

    }

    
     /**
      * @Route("/delete/{id}", name="delete_reclamation",methods={"GET", "POST"} )
     
      */

      public function deleteReclamationAction(Request $request,SerializerInterface $serializer) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        if($reclamation!=null ) {
            $em->remove($reclamation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");}


        


    
 












}


       








//#[Route('/cr', name: 'create', methods: ['GET', 'POST'])]
