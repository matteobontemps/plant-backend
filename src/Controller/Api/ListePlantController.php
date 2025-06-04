<?php 
namespace App\Controller\Api;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use App\Repository\PlanteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\CryptoService;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ListePlantController extends AbstractController{
    #[Route('/ListePlant', name: 'ListePlant', methods: ['GET'])]
    public function afficher_plantes(PlanteRepository $repo): JsonResponse{
       $plantes = $repo->findAll();
        $listPlant = [];

        foreach ($plantes as $plante) {
            $category = $plante->getIdCat();

            $varietesData = [];
            foreach ($plante->getVarietes() as $variete) {
                $varietesData[] = [
                    'idVar' => $variete->getIdVariete(),
                    'libelle' => $variete->getLibelle(),
                    'description' => $variete->getDescription()
                ];
            }

            $listPlant[] = [
                'id' => $plante->getIdPlante(),
                'nom' => $plante->getNom(),
                'desc' => $plante->getDescription(),
                'idcat' => $category ? $category->getIdCat() : null,
                'cat' => $category ? $category->getLibelle() : null,
                'varietes' => $varietesData
            ];
        }

        return new JsonResponse($listPlant, 200);

    }
}