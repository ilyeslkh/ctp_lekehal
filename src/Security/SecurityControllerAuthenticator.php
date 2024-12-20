<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\ClientRepository;

class SecurityControllerAuthenticator extends AbstractAuthenticator
{
    private $authenticationUtils;
    private $clientRepository;
    private $urlGenerator;

    public function __construct(AuthenticationUtils $authenticationUtils, ClientRepository $clientRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->clientRepository = $clientRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = $this->clientRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new AccessDeniedHttpException('Invalid credentials');
        }

        return new Passport(
            new UserBadge($user->getEmail()),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_home_page'));
    }

    public function onAuthenticationFailure(Request $request, \Exception $exception): ?Response
    {
        return new Response('Authentication failed', Response::HTTP_UNAUTHORIZED);
    }
}