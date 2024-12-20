<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class SecurityController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response{
    // Si l'utilisateur est déjà connecté, le rediriger vers la page d'accueil
    if ($this->getUser()) {
        return $this->redirectToRoute('app_home_page');
    }

    // Récupérer les erreurs et le dernier email utilisé
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('security/login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
    ]);
}

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony gère automatiquement la déconnexion
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function requestResetPassword(Request $request): Response
    {
        // Ici, on pourrait créer un formulaire de demande de réinitialisation de mot de passe
        // en envoyant un email avec un lien de réinitialisation.
        return $this->render('security/forgot_password.html.twig');
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token): Response
    {
        // Gérer le réinitialisation du mot de passe avec le token
        $resetToken = $this->resetPasswordHelper->validateTokenAndFetchUser($token);

        // Créer un formulaire pour saisir le nouveau mot de passe
        return $this->render('security/reset_password.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }
}
