<?php 
namespace App\Controller\Api;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\CryptoService;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class AuthController extends AbstractController
{ 

    private CryptoService $crypto;

    public function __construct(CryptoService $crypto)
    {
        $this->crypto = $crypto;
    }
    #[Route('/register', name: 'register')]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (
            empty($data['login']) || !is_string($data['login']) ||
            empty($data['email']) || !is_string($data['email']) ||
            empty($data['password']) || !is_string($data['password']) ||
            empty($data['nom']) || !is_string($data['nom'])
        ) {
            return new JsonResponse(['error' => 'Invalid input'], 400);
        }
        if($em->getRepository(User::class)->findOneBy(['email'=>$this->crypto->encrypt($data['email'])]))
        {
            return new JsonResponse(['error'=>'email already use'],409);
        }
        if($em->getRepository(User::class)->findOneBy(['login'=>$this->crypto->encrypt($data['login'])]))
        {
            return new JsonResponse(['error'=>'login already use'],409);
        }

        try{
        $user = new User();
        $user->setIdUser(uniqid());
        $user->setLogin($this->crypto->encrypt($data['login']));
        $user->setEmail($this->crypto->encrypt($data['email']));
        $hasherP = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hasherP);
        $user->setNom($this->crypto->encrypt($data['nom']));

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'User registered successfully'], 201);
        }catch(\Exception $e){
            return new JsonResponse(['error'=>'server error', 'details'=>$e->getMessage()],500);
        }
    }
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher,SessionInterface $session): JsonResponse
    {  
        $data = json_decode($request->getContent(), true);

        if (!isset($data['login'], $data['password']) && $data['login']!==null && $data['password']!==null ) {
        return new JsonResponse(['error' => 'Invalid input'], 400);
        }
        $encryptlogin = $this->crypto->encrypt($data['login']);
        $user = $em->getRepository(User::class)->findOneBy(['login'=>$encryptlogin]);

        if(!$user || !$passwordHasher->isPasswordValid($user, $data['password']))
        {
            return new JsonResponse(['error' => 'Identifiants incorrects'], 401);
        }

        $session->set('user_id', $user->getIdUser());
        $session->set('login',  $this->crypto->decrypt($user->getLogin()));
        $session->set('email',  $this->crypto->decrypt($user->getEmail()));
        $session->set('nom',  $this->crypto->decrypt($user->getNom()));
        return new JsonResponse([
            'message' => 'Login successful',
            'UserId' => $session->get('user_id'),
            'login' => $session->get('login'),
            'email' => $session->get('email'),
            'Nom' => $session->get('nom')
        ], 200);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request, SessionInterface $session)
    {
        $session->invalidate(); 
        return new JsonResponse(['message' => 'Déconnecté avec succès'], 200);
    }
}