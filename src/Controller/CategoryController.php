<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\FormationRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends AbstractController
{
    #[Route('/index1', name: 'index1')]
    public function home(CategoryRepository $CategoryRepository): Response
    {
        $Categorys=$CategoryRepository->findAll();
        return $this->render('Category/index1.html.twig', [
            'Categorys' => $Categorys,
        ]);
    }

    #[Route('/addCategory', name: 'addCategory')]
    public function addCategory(Request $request, ManagerRegistry $doctrine): Response
    {
        $Category = new Category();
        $form = $this->createForm(CategoryType::class, $Category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($Category);
            $em->flush();
             return $this->redirectToRoute('index1');
        }

        return $this->render('Category/new1.html.twig', [
             'form' => $form->createView(),
        ]);
    }
    #[Route('/detailCategory/{id}', name: 'show1')]
    public function detailCategory(CategoryRepository $repo,$id): Response
    {
        $result=$repo->find($id);
        return $this->render('Category/show1.html.twig', [
            'Category' => $result,
        ]);
    }

    
    #[Route('/updateCategory/{id}', name: 'update1')]
public function updateCategory(Request $request, ManagerRegistry $doctrine, $id): Response
{
    $em = $doctrine->getManager();
    $category = $em->getRepository(Category::class)->find($id);

    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('index1');
    }

    return $this->render('Category/edit1.html.twig', [
        'Category' => $category,
        'form' => $form->createView(),
    ]);
}

    #[Route('/removeCategory/{id}', name: 'remove1')]
public function deleteCategory(ManagerRegistry $doctrine, CategoryRepository $categoryRepo, FormationRepository $formationRepo, $id): Response
{
    $em = $doctrine->getManager();
    $category = $categoryRepo->find($id);

    // Get all formations related to the category
    $formations = $formationRepo->findBy(['category' => $category]);

    // Remove each formation
    foreach ($formations as $formation) {
        $em->remove($formation);
    }

    // Remove the category
    $em->remove($category);
    $em->flush();

    return $this->redirectToRoute('index1');
}
#[Route("/AllCategories", name: "list1")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et CategoryRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getCategorys(CategoryRepository $repo, SerializerInterface $serializer)
    {
        $Categorys = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* Categorys en  tableau associatif simple.
        // $CategorysNormalises = $normalizer->normalize($Categorys, 'json', ['groups' => "Categorys"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($CategorysNormalises);

        $json = $serializer->serialize($Categorys, 'json', ['groups' => "Categorys"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }

    
    
    
    #[Route("addCategoryJSON/new", name: "addCategoryJSON")]
    public function addCategoryJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Category = new Category();
        $Category->setTitre($req->get('titre'));
        
        $em->persist($Category);
        $em->flush();

        $jsonContent = $Normalizer->normalize($Category, 'json', ['groups' => 'Categorys']);
        return new Response(json_encode($jsonContent));
    }
    #[Route("updateCategoryJSON/{id}", name: "updateCategoryJSON")]
    public function updateCategoryJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Category = $em->getRepository(Category::class)->find($id);
        $Category->setTitre($req->get('titre'));
        
       
        $em->flush();

        $jsonContent = $Normalizer->normalize($Category, 'json', ['groups' => 'Categorys']);
        return new Response("Category updated successfully " . json_encode($jsonContent));
    }

    #[Route("deleteCategoryJSON/{id}", name: "deleteCategoryJSON")]
    public function deleteCategoryJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Category = $em->getRepository(Category::class)->find($id);
        $em->remove($Category);
        $em->flush();
        $jsonContent = $Normalizer->normalize($Category, 'json', ['groups' => 'Categorys']);
        return new Response("Category deleted successfully " . json_encode($jsonContent));
    
    }
}
