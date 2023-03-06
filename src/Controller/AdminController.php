<?php

declare(strict_types=1);

namespace App\Controller;

// Other code follows


use App\Entity\FormationLike;
use App\Repository\FormationLikeRepository;
use App\Data\SearchData;
use App\Form\SearchForm;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Formation;
use App\Form\FormationType;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use TCPDF;
use Endroid\QrCode\QrCode;
use Dompdf\Options;
use Dompdf\Dompdf;

class AdminController extends AbstractController
{
    public function index()
    {
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        
        return $this->render('main.html.twig', [
            'formations' => $formations,
        ]);
    }
    

 

   

    #[Route('/admin', name: 'admin')]
    public function home(FormationRepository $formationRepository): Response
    {
       
        $formations=$formationRepository->findAll();
       
            return $this->render('admin/formation.html.twig', [
                'formations' => $formations,
               
            ]);
            
  
    }
   
    #[Route('/detailFormation/{id}', name: 'formationshow')]
    public function detailFormation(FormationRepository $repo,$id): Response
    {
        $result=$repo->find($id);
        return $this->render('admin/formationshow.html.twig', [
            'Formation' => $result,
        ]);
    }
    #[Route('/addFormation', name: 'formationnew')]
    public function addFormation(Request $request, ManagerRegistry $doctrine): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($formation);
            $em->flush();
            
            
             return $this->redirectToRoute('admin');
        }
       
        return $this->render('admin/formationnew.html.twig', [
             'form' => $form->createView(),
        ]);
 
    }
    #[Route('/updateformation/{id}', name: 'formationupdate')]
    public function updateformation(Request $request, ManagerRegistry $doctrine, int $id, FormationRepository $formationRepository): Response
    {
        $formation = $formationRepository->find($id);
    
        if (!$formation) {
            throw $this->createNotFoundException('The formation with id '.$id.' does not exist');
        }
    
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute('admin');
        }
    
        return $this->render('admin/formationupdate.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/removeFormation/{id}', name: 'formationremove')]
    public function deleteFormation(ManagerRegistry $doctrine, FormationRepository $repo,$id): Response
    {
        $em=$doctrine->getManager();
        $result=$repo->find($id);
        $em->remove($result);
        $em->flush(); 

        return $this->redirectToRoute('admin');
 
    }
      /**
 * @Route("stats", name="stats")
 */
public function formationsStats(FormationRepository $formationRepository): Response
{
    $formationsCountByCategory = $formationRepository->getFormationsCountByCategory();

    return $this->render('admin/stats.html.twig', [
        'formationsCountByCategory' => $formationsCountByCategory,
    ]);
}



 /**
 * @Route("/generate-pdf", name="generate_pdf")
 */
public function generatePdf()
{
    // start output buffering
    ob_start();

    // create new PDF document
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator('Your Name');
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Formation Details');

    // set default header data
    $pdf->SetHeaderData('', 0, 'Formation Details', '');

    // set header and footer fonts
    $pdf->setHeaderFont(Array('helvetica', '', 14));
    $pdf->setFooterFont(Array('helvetica', '', 10));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont('courier');

    // set margins
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(5);

    // set auto page breaks
    $pdf->SetAutoPageBreak(true, 25);

    // set image scale factor
    $pdf->setImageScale(1.25);

    // set some language-dependent strings
    $pdf->setLanguageArray(Array('a_meta_charset' => 'UTF-8'));

    // add a page
    $pdf->AddPage();

    // set font
    $pdf->SetFont('times', '', 12);

    // Get the formation details from the database
    $formation = $this->getDoctrine()->getRepository(Formation::class)->findOneById(44); // Replace 1 with the actual ID of the formation you want to generate the PDF for
    // set some content
    $pdf->Write(5, '<h1>Formation Details</h1>');
$pdf->Write(5, '<table border="1">');
$pdf->Write(5, '<thead><tr><th>Field</th><th>Value</th></tr></thead>');
$pdf->Write(5, '<tbody>');
$pdf->Write(5, '<tr><th>Id</th><td>'.($formation ? $formation->getId() : '').'</th></tr>');
$pdf->Write(5, '<tr><th>Category</th><td>'.($formation && $formation->getCategory() ? $formation->getCategory()->getTitre() : '').'</th></tr>');
$pdf->Write(5, '<tr><th>Nom</th><td>'.($formation ? $formation->getNom() : '').'</th></tr>');
$pdf->Write(5, '<tr><th>Description</th><td>'.($formation ? $formation->getDescription() : '').'</th></tr>');
$pdf->Write(5, '<tr><th>Photo</th><td>'.($formation ? $formation->getPhoto() : '').'</th></tr>');
$pdf->Write(5, '<tr><th>Meet</th><td>'.($formation? $formation->getMeet() : '').'</th></tr>');
$pdf->Write(5, '<tr><th>Prix</th><td>'.($formation ? $formation->getPrix() : '').'</th></tr>');
$pdf->Write(5, '</tbody>');
$pdf->Write(5, '</table>');


    // output the PDF as a response
    return new Response($pdf->Output('formation_details.pdf', 'D'), 200, array(
        'Content-Type' => 'application/pdf'
    ));
}

public function showFormation(Formation $formation)
{
    return $this->render('admin/formationshow.html.twig', [
        'formation' => $formation,
    ]);
}



public function formationAction($id)
{
    $formation = $this->getDoctrine()
        ->getRepository(Formation::class)
        ->find($id);

    return $this->render('admin/formation.html.twig', [
        'formation' => $formation,
    ]);
}




//Exporter pdf (composer require dompdf/dompdf)
    /**
     * @Route("/pdf", name="PDF_Formation", methods={"GET"})
     */
    public function pdf(FormationRepository $FormationRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('admin/pdf.html.twig', [
            'Formations' => $FormationRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("ListeDesFormation.pdf", [
            "Formation" => true
        ]);
    }
    /**
     * @Route("/search", name="exsearch")
     */
    public function searchPlanajax(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Formation::class);
        $requestString = $request->get('searchValue');
        $nom = $repository->findPlanBySujet($requestString);
        return $this->render('admin/utilajax.html.twig', [
            'formation' => $nom,
        ]);
    }
    #[Route('/admin/sort', name: 'formation_sort')]
    public function sort(FormationRepository $formationRepository, Request $request): Response
    {
        // Get the sort order from the request, default to "asc" if not set
        $sortOrder = $request->query->get('sortOrder', 'asc');

        // Get the formations sorted by name and price
        $formations = $formationRepository->findBy([], [
            'nom' => $sortOrder,
            'prix' => $sortOrder,
        ]);

        return $this->render('admin/formation.html.twig', [
            'formations' => $formations,
            'sortOrder' => $sortOrder,
        ]);
        
    }
    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
public function new(Request $request, MailerInterface $mailer): Response
{
    $formation = new Formation();
    $form = $this->createForm(FormationType::class, $formation);

    if ($request->isMethod('POST')) {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from($this->getParameter('app.email.from'))
                ->to($this->getParameter('app.email.to'))
                ->subject('Formation ajoutée')
                ->htmlTemplate('admin/email.html.twig')
                ->context([
                    'formation' => $formation,
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('admin');
        }
    }

    return $this->render('admin/formationnew.html.twig', [
        'formation' => $formation,
        'form' => $form->createView(),
    ]);
}


/**
     * @Route("/{id}/like", name="formation_like")
     *
     * @param Formation $formation
     * @param EntityManagerInterface $manager
     * @param FormationLikeRepository $likesRepo
     */

     public function like(Formation $formation, EntityManagerInterface $manager, FormationLikeRepository $likesRepo): Response
     {
         $like = new FormationLike();
         $like->setFormation($formation);
 
         $manager->persist($like);
         $manager->flush();
 
         $likesCount = $likesRepo->count(['formation' => $formation]);
 
         return $this->json([
             'code' => 200,
             'message' => 'Like bien ajouté',
             'likes' => $likesCount,
         ]);
     }
    
}
