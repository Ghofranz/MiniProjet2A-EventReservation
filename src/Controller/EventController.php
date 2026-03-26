<?php
// src/Controller/EventController.php
namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EventRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

class EventController extends AbstractController
{
    // ─── Liste de tous les événements ───────────────────────────────────────
    #[Route('/', name: 'event_index')]
    public function index(EventRepository $repo): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $repo->findAll(),
        ]);
    }

    // ─── Détail d'un événement + formulaire de réservation ──────────────────
    #[Route('/event/{id}', name: 'event_show', requirements: ['id' => '\d+'])]
    public function show(
        int $id,
        EventRepository $repo,
        ReservationRepository $reservationRepo,
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer,
        Environment $twig
    ): Response {
        $event = $repo->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement introuvable.');
        }

        $now    = new \DateTimeImmutable();
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
                $this->addFlash('error', 'Impossible de réserver : il n\'y a plus de places disponibles.');
            } elseif ($form->isValid()) {

                // ── SÉCURITÉ : Vérification doublon (même email + même événement) ──
                $existing = $reservationRepo->findOneBy([
                    'email' => $reservation->getEmail(),
                    'event' => $event,
                ]);

                if ($existing) {
                    $this->addFlash(
                        'error',
                        sprintf(
                            'L\'adresse %s a déjà une réservation pour cet événement.',
                            $reservation->getEmail()
                        )
                    );
                } else {
                    // Décrémenter les places
                    $event->setSeats($event->getSeats() - 1);
                    $em->persist($reservation);
                    $em->persist($event);
                    $em->flush();

                    // ── MAIL : Envoi de la confirmation ──────────────────────────
                    $this->sendConfirmationEmail($mailer, $twig, $reservation);

                    $this->addFlash('success', '🎉 Réservation confirmée ! Un email vous a été envoyé.');

                    return $this->redirectToRoute('reservation_confirm', [
                        'id' => $reservation->getId(),
                    ]);
                }
            }
        }

        return $this->render('event/show.html.twig', [
            'event'           => $event,
            'reservationForm' => $form,
            'isPast'          => $isPast,
            'isFull'          => $isFull,
        ]);
    }

    // ─── Page de confirmation ────────────────────────────────────────────────
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

    // ─── Helper privé : envoi mail ───────────────────────────────────────────
    private function sendConfirmationEmail(
        MailerInterface $mailer,
        Environment $twig,
        Reservation $reservation
    ): void {
        // dd('sendConfirmationEmail appelée', $reservation->getEmail());
        $htmlBody = $twig->render('emails/reservation_confirm.html.twig', [
            'reservation' => $reservation,
        ]);

        $email = (new Email())
            ->from('noreply@eventres.local')
            ->to($reservation->getEmail())
            ->subject(sprintf('✅ Confirmation – %s', $reservation->getEvent()->getTitle()))
            ->html($htmlBody);

        // try {
        //     $mailer->send($email);
        // } catch (\Throwable $e) {
        //     // Ne pas bloquer la réservation si l'envoi échoue
        //     // (loguer en production)
        // }
        // dd($email);
            $mailer->send($email);
    }
// private function sendConfirmationEmail(
//     MailerInterface $mailer,
//     Environment $twig,
//     Reservation $reservation
// ): void {
//     $email = (new Email())
//         ->from('from@example.org')
//         ->to('test@gmail.com')
//         ->subject('Testing transport')
//         ->text('Testing body');

//     $mailer->send($email);
// }
    
    // ---test mail-----------------------------------
    #[Route('/test-mail', name: 'test_mail')]
public function testMail(MailerInterface $mailer): Response
{
    $email = (new Email())
        ->from('noreply@eventres.local')
        ->to('test@example.com')
        ->subject('Test Mailpit')
        ->html('<h1>Mail de test</h1><p>Si vous voyez ceci, Mailpit fonctionne.</p>');

    $mailer->send($email);

    return new Response('Mail envoyé');
}
}