<?php

declare(strict_types=1);

namespace App\Controller;

// Other code follows

use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;




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
class AdminController extends AbstractController
{
    public function index()
    {
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        
        return $this->render('main.html.twig', [
            'formations' => $formations,
        ]);
    }
    

 

    public function detail(Formation $formation, Request $request, EntityManagerInterface $entityManager)
    {
        $rating = new Rating();
        $rating->setFormation($formation);
    
        $form = $this->createForm(RatingType::class, $rating);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rating);
            $entityManager->flush();
    
            $this->addFlash('success', 'Rating added successfully.');
        }
    
        $averageRating = $formation->getAverageRating();
    
        return $this->render('formation/detail.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            'averageRating' => $averageRating,
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


#[Route('/rating/{id}', name: 'formationrating')]
public function formationRating(FormationRepository $repo, EntityManagerInterface $em, int $id, Request $request): Response
{
    $formation = $repo->find($id);

    if (!$formation) {
        throw $this->createNotFoundException('The formation with id '.$id.' does not exist');
    }

    // Handle the form submission
    $form = $this->createFormBuilder()
        ->add('rating', ChoiceType::class, [
            'choices' => [
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
            ],
            'label' => 'Rate this formation',
            'required' => true,
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Submit',
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $rating = $form->get('rating')->getData();
        $formation->setRating($rating);
        $em->flush();

        return $this->redirectToRoute('formationshow', ['id' => $id]);
    }

    // Render the form
    return $this->render('admin/formationrating.html.twig', [
        'formation' => $formation,
        'form' => $form->createView(),
    ]);
}

}
