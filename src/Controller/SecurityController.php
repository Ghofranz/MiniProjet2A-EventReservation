<?php
// src/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si déjà connecté → rediriger
        // if ($this->getUser()) {
        //     if ($this->isGranted('ROLE_ADMIN')) {
        //         return $this->redirectToRoute('admin_dashboard');
        //     }
        //     return $this->redirectToRoute('event_index');
        // }
if ($this->getUser()) {
    return $this->redirectToRoute('event_index');
}
        // Récupère l'erreur de login s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier username saisi (pour repré-remplir le champ)
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony intercepte cette route automatiquement
        // Ce code ne s'exécute jamais
        throw new \LogicException('Intercepté par le firewall Symfony.');
    }
}