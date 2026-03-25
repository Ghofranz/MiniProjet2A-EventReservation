<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Créer un admin
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Créer un user normal
        $user = new User();
        $user->setUsername('user');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'user123'));
        $manager->persist($user);

        // Créer 3 événements
        $events = [
            ['Tech Summit 2026', 'Grande conférence sur l\'IA et le DevOps', '2026-06-15', 'Tunis, Palais des Congrès', 200],
            ['Hackathon IoT', 'Compétition de développement IoT sur 48h', '2026-07-20', 'ISSAT Sousse', 100],
            ['Workshop Cybersécurité', 'Formation pratique sur la sécurité des APIs', '2026-08-10', 'Sousse, Hôtel Tej', 50],
        ];

        foreach ($events as [$title, $desc, $date, $loc, $seats]) {
            $event = new Event();
            $event->setTitle($title);
            $event->setDescription($desc);
            $event->setDate(new \DateTime($date));
            $event->setLocation($loc);
            $event->setSeats($seats);
            $manager->persist($event);
        }

        $manager->flush();
    }
}