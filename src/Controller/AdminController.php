<?php
// src/Controller/AdminController.php
namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    // ─── Tableau de bord ────────────────────────────────────────────────────
    // #[Route('/dashboard', name: 'admin_dashboard')]
    // public function dashboard(
    //     EventRepository $eventRepo,
    //     ReservationRepository $reservationRepo
    // ): Response {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');

    //     return $this->render('admin/dashboard.html.twig', [
    //         'totalEvents'       => count($eventRepo->findAll()),
    //         'totalReservations' => count($reservationRepo->findAll()),
    //         'latestEvents'      => $eventRepo->findBy([], ['date' => 'DESC'], 5),
    //     ]);
    // }
    #[Route('/dashboard', name: 'admin_dashboard')]
public function dashboard(
    EventRepository $eventRepo,
    ReservationRepository $reservationRepo
): Response {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $allEvents = $eventRepo->findBy([], ['date' => 'DESC']);
    $latestEvents = array_slice($allEvents, 0, 100);

    $upcomingCount = 0;
    $finishedCount = 0;
    $now = new \DateTime();

    foreach ($allEvents as $event) {
        if ($event->getDate() && $event->getDate() > $now) {
            $upcomingCount++;
        } else {
            $finishedCount++;
        }
    }

    return $this->render('admin/dashboard.html.twig', [
        'totalEvents'       => count($allEvents),
        'totalReservations' => count($reservationRepo->findAll()),
        'latestEvents'      => $latestEvents,
        'upcomingCount'     => $upcomingCount,
        'finishedCount'     => $finishedCount,
    ]);
}

    // ─── Liste des événements ────────────────────────────────────────────────
    #[Route('/events', name: 'admin_event_index')]
    public function eventIndex(EventRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/event/index.html.twig', [
            'events' => $repo->findBy([], ['date' => 'DESC']),
        ]);
    }

    // ─── Créer un événement ──────────────────────────────────────────────────
    #[Route('/events/new', name: 'admin_event_new')]
    public function eventNew(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $event = new Event();
        $form  = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload d'image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename     = $slugger->slug($originalFilename);
                $newFilename      = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );
                $event->setImage($newFilename);
            }

            $em->persist($event);
            $em->flush();

            $this->addFlash('success', '✅ Événement créé avec succès !');
            return $this->redirectToRoute('admin_event_index');
        }

        return $this->render('admin/event/form.html.twig', [
            'form'  => $form,
            'event' => $event,
            'title' => 'Nouvel événement',
        ]);
    }

    // ─── Modifier un événement ───────────────────────────────────────────────
    #[Route('/events/{id}/edit', name: 'admin_event_edit', requirements: ['id' => '\d+'])]
    public function eventEdit(
        int $id,
        EventRepository $repo,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $event = $repo->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement introuvable.');
        }

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                // Supprimer l'ancienne image si elle existe
                if ($event->getImage()) {
                    $oldPath = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $event->getImage();
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename     = $slugger->slug($originalFilename);
                $newFilename      = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );
                $event->setImage($newFilename);
            }

            $em->flush();

            $this->addFlash('success', '✅ Événement modifié avec succès !');
            return $this->redirectToRoute('admin_event_index');
        }

        return $this->render('admin/event/form.html.twig', [
            'form'  => $form,
            'event' => $event,
            'title' => 'Modifier : ' . $event->getTitle(),
        ]);
    }

    // ─── Supprimer un événement ──────────────────────────────────────────────
    #[Route('/events/{id}/delete', name: 'admin_event_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function eventDelete(
        int $id,
        EventRepository $repo,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $event = $repo->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement introuvable.');
        }

        // Vérification du token CSRF anti-suppression accidentelle
        if ($this->isCsrfTokenValid('delete-event-' . $id, $request->request->get('_token'))) {
            // Supprimer l'image associée
            if ($event->getImage()) {
                $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $event->getImage();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $em->remove($event);
            $em->flush();
            $this->addFlash('success', '🗑️ Événement supprimé.');
        }

        return $this->redirectToRoute('admin_event_index');
    }

    // ─── Réservations d'un événement ────────────────────────────────────────
    #[Route('/reservations', name: 'admin_reservation_index')]
    public function reservationIndex(ReservationRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/reservation/index.html.twig', [
            'reservations' => $repo->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    // ─── Supprimer une réservation ───────────────────────────────────────────
    #[Route('/reservations/{id}/delete', name: 'admin_reservation_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function reservationDelete(
        int $id,
        ReservationRepository $repo,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $reservation = $repo->find($id);
        if ($reservation && $this->isCsrfTokenValid('delete-res-' . $id, $request->request->get('_token'))) {
            $em->remove($reservation);
            $em->flush();
            $this->addFlash('success', '🗑️ Réservation supprimée.');
        }

        return $this->redirectToRoute('admin_reservation_index');
    }
}