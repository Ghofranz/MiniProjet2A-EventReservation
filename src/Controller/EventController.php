<?php
// src/Controller/EventController.php
namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    // ─── Liste de tous les événements ───────────────────────────────────────
    #[Route('/', name: 'event_index')]
    public function index(EventRepository $repo): Response
    {
        $events = $repo->findAll();

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    // ─── Détail d'un événement + formulaire de réservation ──────────────────
    // #[Route('/event/{id}', name: 'event_show', requirements: ['id' => '\d+'])]
    // public function show(
    //     int $id,
    //     EventRepository $repo,
    //     Request $request,
    //     EntityManagerInterface $em
    // ): Response {
    //     $event = $repo->find($id);

    //     if (!$event) {
    //         throw $this->createNotFoundException('Événement introuvable.');
    //     }

    //     // Créer une nouvelle réservation liée à cet événement
    //     $reservation = new Reservation();
    //     $reservation->setEvent($event);

    //     $form = $this->createForm(ReservationType::class, $reservation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em->persist($reservation);
    //         $em->flush();

    //         $this->addFlash('success', '🎉 Réservation confirmée !');
    //         return $this->redirectToRoute('reservation_confirm', [
    //             'id' => $reservation->getId(),
    //         ]);
    //     }

    //     return $this->render('event/show.html.twig', [
    //         'event'           => $event,
    //         'reservationForm' => $form,
    //     ]);
    // }
#[Route('/event/{id}', name: 'event_show', requirements: ['id' => '\d+'])]
public function show(
    int $id,
    EventRepository $repo,
    Request $request,
    EntityManagerInterface $em
): Response {
    $event = $repo->find($id);

    if (!$event) {
        throw $this->createNotFoundException('Événement introuvable.');
    }

    $now = new \DateTimeImmutable();
    $isPast = $event->getDate() !== null && $event->getDate() < $now;
    $isFull = $event->getSeats() !== null && $event->getSeats() <= 0;

    $reservation = new Reservation();
    $reservation->setEvent($event);

    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if ($isPast) {
            $this->addFlash('error', 'Les réservations sont fermées : cet événement est déjà passé.');
        } elseif ($isFull) {
            $this->addFlash('error', 'Impossible de réserver : il n’y a plus de places disponibles.');
        } elseif ($form->isValid()) {
            $event->setSeats($event->getSeats() - 1);

            $em->persist($reservation);
            $em->persist($event);
            $em->flush();

            $this->addFlash('success', '🎉 Réservation confirmée !');

            return $this->redirectToRoute('reservation_confirm', [
                'id' => $reservation->getId(),
            ]);
        }
    }

    return $this->render('event/show.html.twig', [
        'event' => $event,
        'reservationForm' => $form,
        'isPast' => $isPast,
        'isFull' => $isFull,
    ]);
}
    // ─── Page de confirmation après réservation ─────────────────────────────
    #[Route('/reservation/confirm/{id}', name: 'reservation_confirm', requirements: ['id' => '\d+'])]
    public function confirm(int $id, EntityManagerInterface $em): Response
    {
        $reservation = $em->getRepository(Reservation::class)->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Réservation introuvable.');
        }

        return $this->render('event/confirm.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}