<?php

namespace App\Controller;
use App\Entity\Abonnement;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation1')]
class ReservationController extends AbstractController
{
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $reservation = new Reservation();
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $abonnement = $reservation->getAbonnement();
        $reservationsRestantes = $abonnement->getReservationsRestantes();

        if ($reservationsRestantes === 0) {
            return $this->render('reservation/renouvlement.html.twig');
        }

        $abonnement->setReservationsRestantes($reservationsRestantes - 1);
        $entityManager->persist($abonnement);
        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->redirectToRoute('app_client');
    }

    return $this->renderForm('reservation/new.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
    ]);
}   

}
