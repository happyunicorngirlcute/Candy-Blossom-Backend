<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ResendMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[Route('/register/initiate', name: 'register_initiate', methods: ['POST'])]
    public function initiate(
        Request $request,
        EntityManagerInterface $em,
        ResendMailer $resendMailer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['name']) || empty($data['email']) || empty($data['name'])) {
            return $this->json(['error' => 'I need your name and email.'], 400);
        }

        $existing = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existing) {
            if ($existing->isVerified() && $existing->getPassword()) {
                return $this->json(['error' => 'I already have an account with this email'], 409);
            }
            // If user exists but not verified, we can reuse it/resend email
            $user = $existing;
        } else {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setName($data['name']);
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(false);
        }

        // token + expiration
        $token = bin2hex(random_bytes(32));
        $user->setVerificationToken($token);
        $user->setVerificationExpiresAt(new \DateTimeImmutable('+1 hour'));

        $em->persist($user);
        $em->flush();

        $link = $_ENV['FRONTEND_URL'] . '/verify-email?token=' . $token;

        $resendMailer->sendEmail(
            $user->getEmail(),
            'Verify your email - Candy Blossom',
            "
            <div style='font-family:Arial;background:#f6f7fb;padding:40px;'>
                <div style='max-width:520px;margin:auto;background:white;padding:30px;border-radius:12px'>
                    <h2 style='margin-bottom:10px;'>Welcome.</h2>
                    <p style='color:#4a5568;line-height:1.5;'>
                        Click the button below to verify your email. After that, you'll be able to set your password and start using Candy Blossom.
                    </p>
                    <div style='text-align:center;margin:30px 0;'>
                        <a href='{$link}'
                           style='background:#F2B5CE;color:#fff;padding:12px 22px;
                           text-decoration:none;border-radius:8px;font-weight:bold;'>
                            Verify my email
                        </a>
                    </div>
                </div>
            </div>
            "
        );

        return $this->json(['message' => 'Verification email sent!']);
    }

    #[Route('/register/complete', name: 'register_complete', methods: ['POST'])]
    public function complete(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['password']) || empty($data['email']) || empty($data['password'])) {
            return $this->json(['error' => 'I need your email and password.'], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if (!$user || !$user->isVerified()) {
            return $this->json(['error' => 'User not found or not verified.'], 400);
        }

        if ($user->getPassword()) {
            return $this->json(['error' => 'Account already completed.'], 400);
        }

        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $em->flush();

        return $this->json([
            'message' => 'Account completed! You are now logged in.',
            'token' => $jwtManager->create($user),
            'user' => [
                'email' => $user->getEmail(),
                'name' => $user->getName(),
            ],
        ]);
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em,
        ResendMailer $resendMailer
    ): JsonResponse {
        // This method can be kept for backward compatibility or removed if we strictly use the new flow.
        // For now, let's keep it but ideally we transition everything.
        return $this->initiate($request, $em, $resendMailer);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        // Lexik JWT intercepte cette route automatiquement
        return $this->json(['message' => 'Login handled by Lexik'], 200);
    }

    #[Route('/user/me', name: 'user_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        return $this->json([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/user/me', name: 'user_me_update', methods: ['PUT'])]
    public function updateMe(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name']) && !empty(trim($data['name']))) {
            $user->setName(trim($data['name']));
        }
        if (isset($data['email']) && !empty(trim($data['email']))) {
            $existing = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if ($existing && $existing->getId() !== $user->getId()) {
                return $this->json(['error' => 'Email already in use'], 409);
            }
            $user->setEmail(trim($data['email']));
        }

        $em->flush();

        return $this->json([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/user/password', name: 'user_password', methods: ['PUT'])]
    public function updatePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['currentPassword'], $data['newPassword']) || empty($data['currentPassword']) || empty($data['newPassword'])) {
            return $this->json(['error' => 'Current password and new password are required'], 400);
        }

        if (!$passwordHasher->isPasswordValid($user, $data['currentPassword'])) {
            return $this->json(['error' => 'Current password is incorrect'], 403);
        }

        $user->setPassword($passwordHasher->hashPassword($user, $data['newPassword']));
        $em->flush();

        return $this->json(['message' => 'Password updated successfully']);
    }

    #[Route('/verify-email', name: 'verify_email', methods: ['GET'])]
    public function verifyEmail(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        $token = $request->query->get('token');

        if (!$token) {
            return $this->json([
                'error' => 'I need a token!'
            ], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy([
            'verificationToken' => $token
        ]);

        if (!$user) {
            return $this->json([
                'error' => 'I need a valid token!'
            ], 400);
        }

        // expiration check
        if ($user->getVerificationExpiresAt() < new \DateTimeImmutable()) {
            $em->remove($user);
            $em->flush();
            return $this->json([
                'error' => 'Your verification token has expired! I have deleted your account, please sign up again'
            ], 410);
        }

        // verify user
        $user->setIsVerified(true);
        $user->setVerificationToken(null);
        $user->setVerificationExpiresAt(null);

        $em->flush();

        return $this->json([
            'message' => 'I have verified your email!',
            'email' => $user->getEmail()
        ]);
    }
}
