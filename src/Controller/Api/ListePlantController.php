<?php 
namespace App\Controller\Api;
use App\Repository\PlanteRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListePlantController extends AbstractController{

    private function formatPlantes(array $plantes) : array {
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
        return $listPlant;
    }

    #[Route('/ListePlant', name: 'ListePlant', methods: ['GET'])]
    public function afficher_plantes(PlanteRepository $repo): JsonResponse{
       $plantes = $repo->findAll();
        return new JsonResponse($this->formatPlantes($plantes), 200);
    }

    #[Route('/ListePlant/{idCat}', name: 'ListePlantFiltre', methods: ['GET'])]
    public function afficher_plantes_filtre(PlanteRepository $repo, int $idCat): JsonResponse{
       $plantes = $repo->findByCategorie($idCat);
        return new JsonResponse($this->formatPlantes($plantes), 200);

    }    
}