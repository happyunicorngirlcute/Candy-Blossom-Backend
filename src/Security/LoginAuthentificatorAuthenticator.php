<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use App\Entity\User;

class LoginAuthentificatorAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $data = json_decode($request->getContent(), true);

        return new Passport(
            // ✅ FIX ICI UNIQUEMENT
            new UserBadge($data['email']),
            new PasswordCredentials($data['password'])
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();

        // 🚨 BLOCK NON VERIFIED USER (inchangé)
        if (!$user->isVerified()) {
            return new JsonResponse([
                'error' => 'On m\'a dit que ton email n\'est pas vérifié! Vérifie ton email pour activer ton compte!'
            ], Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse([
            'message' => 'Welcome, ' . $user->getName() . '!',
            'user' => [
                'email' => $user->getUserIdentifier(),
                'name' => $user->getName(),
            ]
        ]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'error' => 'Désoler, je n\'est pas pus te reconnaitre! L\'érreur qu\'on ma donnée: getmessage: ' . $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }
}
