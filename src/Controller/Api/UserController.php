<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CryptoService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

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

    #[Route('/api/profile/update', name: 'user_profile_update', methods: ['POST'])]
    public function updateProfile(Request $request, SessionInterface $session, EntityManagerInterface $em, CryptoService $crypto): JsonResponse
    {
        if (!$session->has('user_id')) {
            return new JsonResponse(['error' => 'Accès non autorisé'], 401);
        }

        $data = json_decode($request->getContent(), true);
        $userId = $session->get('user_id');

        $user = $em->getRepository(User::class)->findOneBy(['idUser' => $userId]);
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur introuvable'], 404);
        }

        if (isset($data['nom'])) $user->setNom($crypto->encrypt($data['nom']));
        if (isset($data['email'])) {
            $encryptEmail = $crypto->encrypt($data['email']);
            $userEmail = $em->getRepository(User::class)->findOneBy(['email' => $encryptEmail]);
            if (!$userEmail || $userEmail->getIdUser() == $user->getIdUser()) {
                $user->setEmail($encryptEmail);
            } else {
                return new JsonResponse(['error' => 'Email déjà pris !'], 409);
            }
        }
        if (isset($data['login'])) {
            $encryptLogin = $crypto->encrypt($data['login']);
            $userLogin = $em->getRepository(User::class)->findOneBy(['login' => $encryptLogin]);
            if (!$userLogin || $userLogin->getIdUser() == $user->getIdUser()) {
                $user->setLogin($encryptLogin);
            } else {
                return new JsonResponse(['error' => 'Login déjà pris !'], 409);
            }
        }

        $em->flush();

        // Mettre à jour la session
        $session->set('nom', $data['nom']);
        $session->set('email', $data['email']);
        $session->set('login', $data['login']);

        return new JsonResponse(['message' => 'Informations mises à jour'], 200);
    }
}
