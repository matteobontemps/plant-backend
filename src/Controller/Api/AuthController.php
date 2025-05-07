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
         if(!isset($data['login'], $data['email'], $data['password'], $data['nom'])){
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
    #[Route('/login', name: 'login')]
    public function login()
    {
        

    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard()
    {
        
        return $this->render('dashboard.html.twig');
    }
}