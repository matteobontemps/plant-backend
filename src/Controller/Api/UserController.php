<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/profile', name: 'user_profile', methods: ['GET'])]
    public function profile(SessionInterface $session): JsonResponse
    {
        if (!$session->has('user_id')) {
            return new JsonResponse(['error' => 'Acces non autorise.'], 401);
        }

        $userId = $session->get('user_id');
        $login = $session->get('login');
        $email = $session->get('email');
        $nom = $session->get('nom');

        return new JsonResponse([
            'user_id' => $userId,
            'login' => $login,
            'email' => $email,
            'nom' => $nom
        ], 200);
    }
}
