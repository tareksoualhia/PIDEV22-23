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

class AdminnController extends AbstractController
{
    #[Route('/adminn', name: 'adminn')]
    public function home(CategoryRepository $CategoryRepository): Response
    {
        $Categorys=$CategoryRepository->findAll();
        return $this->render('adminn/category.html.twig', [
            'Categorys' => $Categorys,
        ]);
    }
    #[Route('/admin', name: 'admin')]
    public function home2(FormationRepository $formationRepository): Response
    {
        $formations=$formationRepository->findAll();
        return $this->render('admin/formation.html.twig', [
            'formations' => $formations,
        ]);
    }
    #[Route('/addCategory', name: 'categorynew')]
    public function addCategory(Request $request, ManagerRegistry $doctrine): Response
    {
        $Category = new Category();
        $form = $this->createForm(CategoryType::class, $Category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($Category);
            $em->flush();
             return $this->redirectToRoute('adminn');
        }

        return $this->render('adminn/categorynew.html.twig', [
             'form' => $form->createView(),
        ]);
    }
    #[Route('/detailCategory/{id}', name: 'categoryshow')]
    public function detailCategory(CategoryRepository $repo,$id): Response
    {
        $result=$repo->find($id);
        return $this->render('adminn/categoryshow.html.twig', [
            'Category' => $result,
        ]);
    }

    
    #[Route('/updateCategory/{id}', name: 'categoryupdate')]
public function updateCategory(Request $request, ManagerRegistry $doctrine, $id): Response
{
    $em = $doctrine->getManager();
    $category = $em->getRepository(Category::class)->find($id);

    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('adminn');
    }

    return $this->render('adminn/categoryupdate.html.twig', [
        'Category' => $category,
        'form' => $form->createView(),
    ]);
}

    #[Route('/removeCategory/{id}', name: 'categoryremove')]
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

    return $this->redirectToRoute('adminn');
}}

