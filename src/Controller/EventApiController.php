<?php
namespace App\Controller;

use App\Entity\Evenment;
use App\Entity\Event;
use App\Repository\EvenmentRepository;
use App\Repository\EventRepository;
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




class EventApiController extends AbstractController
{
    private $entityManager;
    private $evenmentrepository;

    public function __construct(EntityManagerInterface $entityManager, EvenmentRepository $eventRepository)
    {
        $this->entityManager = $entityManager;
        $this->evenmentrepository = $eventRepository;
    }
    #[Route('/addEventJSON', name: 'addEventJSON', methods: ['GET', 'POST'])]
    public function addEventJSON( Request $request ,NormalizerInterface $normalizer ){
        $em=$this->getDoctrine()->getManager();
        $event=new Evenment();
        $event->setTitle($request->get('nom'));
        $event->setDateDebut(new \DateTime($request->get('DateDebut')));
        $event->setDateFin(new \DateTime($request->get('DateFin')));
        $em -> persist($event);
        $em->flush();
        $jsonContent=$normalizer->normalize($event, 'json', ['circular_reference_handler' => function ($object) {
            return $object->getId();
        }, 'max_depth' => 1]);
        return new Response("event ajoutéé".json_encode($jsonContent));

    }

    #[Route('/getEVent', name: 'getEVent', methods: ['GET', 'POST'])]
    public function getEVent()
    {

        $event=$this->getDoctrine()->getManager()
            ->getRepository(Event::class)
            ->findAll();

        $data =  array();
        foreach ($event as $key => $p){

            $data[$key]['id']= $p->getId();
            $data[$key]['DateDebut']= $p->getDateDebut();
            $data[$key]['DateFin']= $p->getDateFin();

        }

        return new JsonResponse($data);

    }


    #[Route('/apii/{id}', name: 'show', methods: ['GET', 'POST'])]

    public function show(EvenmentRepository $event, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($event, 'json', ['circular_reference_handler' => function ($object) {
            return $object->getId();
        }, 'max_depth' => 1]);

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }


    #[Route('/cr', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->query->all();

        $event = new Evenment();
        $event->setTitle($data['nom']);
        $event->setDateDebut(new \DateTime($data['dateDebut']));
        $event->setDateFin(new \DateTime($data['dateFin']));


        // Validate the entity
        $errors = $validator->validate($event);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        $json = $serializer->serialize($event, 'json', ['circular_reference_handler' => function ($object) {
            return $object->getId();
        }, 'max_depth' => 1]);

        return new JsonResponse($json, JsonResponse::HTTP_CREATED, [], true);
    }

    #[Route('/cr/{id}', name: 'update', methods: ['PUT'])]
    public function update(Evenment $event, Request $request, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $event->setTitle($data['nom']);
        $event->setDateDebut(new \DateTime($data['dateDebut']));
        $event->setDateFin(new \DateTime($data['dateFin']));
        // Validate the entity
        $errors = $validator->validate($event);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $this->entityManager->flush();

        $json = $serializer->serialize($event, 'json', ['circular_reference_handler' => function ($object) {
            return $object->getId();
        }, 'max_depth' => 1]);

        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/cr/{id}', name: 'delete', methods: ['DELETE'])]

    public function delete(Evenment $event): JsonResponse
    {
        try {
            $this->entityManager->remove($event);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(['error' => 'Unable to delete event'], 400);
        }

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

}
