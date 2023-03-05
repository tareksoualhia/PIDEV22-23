<?php

namespace App\Controller;

use App\Entity\EvenLike;
use App\Entity\Evenment;
use Symfony\Component\Mailer\MailerInterface;
use App\Form\EvenmentType;
use App\Repository\EvenLikeRepository;
use App\Repository\EvenmentRepository;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/eve')]

class EvenmentController extends AbstractController
{
    #[Route('/', name: 'app_evenment_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $evenment = $entityManager
            ->getRepository(Evenment::class)
            ->findAll();
        $evenment = $paginator->paginate(
            $evenment, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('evenment/index.html.twig', [
            'evenement' => $evenment,
        ]);
    }

    #[Route('/new', name: 'app_evenment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenmentRepository $repository,MailerInterface $mailer): Response
    {
        $evenment = new Evenment();
        $form = $this->createForm(EvenmentType::class, $evenment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evenment);
            $entityManager->flush();
            $email = (new  TemplatedEmail())
                ->from('noreplyEVENT@gmail.com')
                // On attribue le destinataire
                ->to('nourchen.hedfi@esprit.tn')
                // On crée le texte avec la vue
                ->subject('evenment ajouté')
                ->htmlTemplate('evenment/email.html.twig')
                ->context([
                    'evenment' => $evenment,
                ]);
            $mailer->send($email);
            return $this->redirectToRoute('app_evenment_index');

        }
        return $this->render('evenment/new.html.twig', [
            'evenment' => $evenment,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_evenment_show', methods: ['GET'])]
    public function show(Evenment $evenment): Response
    {
        return $this->render('evenment/show.html.twig', [
            'evenment' => $evenment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenment $evenment, EvenmentRepository $evenmentRepository): Response
    {
        $form = $this->createForm(EvenmentType::class, $evenment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenmentRepository->save($evenment, true);

            return $this->redirectToRoute('app_evenment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenment/edit.html.twig', [
            'evenment' => $evenment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenment_delete', methods: ['POST'])]
    public function delete(Request $request, Evenment $evenment, EvenmentRepository $evenmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evenment->getId(), $request->request->get('_token'))) {
            $evenmentRepository->remove($evenment, true);
        }

        return $this->redirectToRoute('app_evenment_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/like", name="event_like")
     *
     * @param Evenment $event
     * @param EntityManagerInterface $manager
     * @param EvenLikeRepository $likesRepo
     */

    public function like(Evenment $event, EntityManagerInterface $manager, EvenLikeRepository $likesRepo): Response
    {
        $like = new EvenLike();
        $like->setEvent($event);

        $manager->persist($like);
        $manager->flush();

        $likesCount = $likesRepo->count(['event' => $event]);

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajouté',
            'likes' => $likesCount,
        ]);
    }

}