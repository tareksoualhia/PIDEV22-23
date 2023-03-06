<?php

namespace App\Controller;
use App\Repository\FormationLikeRepository;
use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Options;
use Dompdf\Dompdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


use Symfony\Component\Serializer\Serializer;  




class FormationController extends AbstractController
{
    
    
 
    #[Route('/index', name: 'index')]
    public function home(FormationRepository $formationRepository): Response
    {
        $formations=$formationRepository->findAll();
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }
 
    #[Route('/addFormation', name: 'addFormation')]
    public function addFormation(Request $request, ManagerRegistry $doctrine): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($formation);
            $em->flush();
            
            
             return $this->redirectToRoute('index');
        }
       
        return $this->render('formation/new.html.twig', [
             'form' => $form->createView(),
        ]);
    }
    #[Route('/detailFormation/{id}', name: 'show')]
    public function detailFormation(FormationRepository $repo,$id): Response
    {
        $result=$repo->find($id);
        return $this->render('formation/show.html.twig', [
            'Formation' => $result,
        ]);
    }
 
 /**
     * @Route("/", name="homepage")
     */
    public function index(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findAll();
        
        return $this->render('base.html.twig', [
            'formations' => $formations,
        ]);
    }
    
    #[Route('/updateformation/{id}', name: 'update')]
    public function updateformation(Request $request, ManagerRegistry $doctrine,formation $formation, formationRepository $formationRepository): Response
    {
        $form = $this->createForm(formationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($formation);
            $em->flush();
             return $this->redirectToRoute('index');
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/removeFormation/{id}', name: 'remove')]
    public function deleteFormation(ManagerRegistry $doctrine, FormationRepository $repo,$id): Response
    {
        $em=$doctrine->getManager();
        $result=$repo->find($id);
        $em->remove($result);
        $em->flush(); 

        return $this->redirectToRoute('index');
    }
    #[Route("/AllFormations", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et FormationRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getFormations(FormationRepository $repo, SerializerInterface $serializer)
    {
        $Formations = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* Formations en  tableau associatif simple.
        // $FormationsNormalises = $normalizer->normalize($Formations, 'json', ['groups' => "Formations"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($FormationsNormalises);

        $json = $serializer->serialize($Formations, 'json', ['groups' => "Formations"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }

    
    
    
    #[Route("addFormationJSON/new", name: "addFormationJSON")]
    public function addFormationJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Formation = new Formation();
        $Formation->setNom($req->get('nom'));
        $Formation->setDescription($req->get('description'));
        $Formation->setMeet($req->get('meet'));
        $Formation->setPhoto($req->get('photo'));
        $Formation->setPrix(intval($req->get('prix')));
        $em->persist($Formation);
        $em->flush();

        $jsonContent = $Normalizer->normalize($Formation, 'json', ['groups' => 'Formations']);
        return new Response(json_encode($jsonContent));
    }
    #[Route("updateFormationJSON/{id}", name: "updateFormationJSON")]
    public function updateFormationJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Formation = $em->getRepository(Formation::class)->find($id);
        $Formation->setNom($req->get('nom'));
        $Formation->setDescription($req->get('description'));
        $Formation->setMeet($req->get('meet'));
        $Formation->setPhoto($req->get('photo'));
        $Formation->setPrix($req->get('prix'));
       
        $em->flush();

        $jsonContent = $Normalizer->normalize($Formation, 'json', ['groups' => 'Formations']);
        return new Response("Formation updated successfully " . json_encode($jsonContent));
    }

    #[Route("deleteFormationJSON/{id}", name: "deleteFormationJSON")]
    public function deleteFormationJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Formation = $em->getRepository(Formation::class)->find($id);
        $em->remove($Formation);
        $em->flush();
        $jsonContent = $Normalizer->normalize($Formation, 'json', ['groups' => 'Formations']);
        return new Response("Formation deleted successfully " . json_encode($jsonContent));
    }
    #[Route("/Formation/{id}", name: "Formation")]
    public function FormationId($id, NormalizerInterface $normalizer, FormationRepository $repo)
    {
        $Formation = $repo->find($id);
        $FormationNormalises = $normalizer->normalize($Formation, 'json', ['groups' => "Formations"]);
        return new Response(json_encode($FormationNormalises));
    }

  

    #[Route("/recherche_ajax", name:"recherche_ajax")]
    public function recherche_ajax(Request $request, FormationRepository $sr, SerializerInterface $serializer): JsonResponse
    {
        $requestString = $request->query->get('searchValue');
        $resultats = $sr->findStudentByNsc($requestString);
    
        $context = [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            'circular_reference_limit' => 1,
        ];
    
        $json = $serializer->serialize($resultats, 'json', $context);
    
        return new JsonResponse($json, 200, [], true);
    }
    


    #[Route('/imp', name:'pdf', methods: ['GET'])]
    public function print(FormationRepository $FormationRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html= $this->render('admin/pdf.html.twig', [
            'formations' => $FormationRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("formationPDF.pdf", [
            "Attachment" => true

        ]);
    }

}
