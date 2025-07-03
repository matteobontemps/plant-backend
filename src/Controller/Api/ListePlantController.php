<?php

namespace App\Controller\Api;

use App\Repository\PlanteRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ImageUrlService;

class ListePlantController extends AbstractController
{

    private function formatPlantes(array $plantes, ImageUrlService $imageUrl): array
    {
        $listPlant = [];
        foreach ($plantes as $plante) {
            $category = $plante->getIdCat();

            $varietesData = [];
            foreach ($plante->getVarietes() as $variete) {
                $varietesData[] = [
                    'idVar'       => $variete->getIdVariete(),
                    'libelle'     => $variete->getLibelle(),
                    'description' => $variete->getDescription(),
                    'image'       => $imageUrl->getFileUrl($variete->getImage())
                ];
            }

            $listPlant[] = [
                'id'        => $plante->getIdPlante(),
                'nom'       => $plante->getNom(),
                'desc'      => $plante->getDescription(),
                'idcat'     => $category ? $category->getIdCat() : null,
                'cat'       => $category ? $category->getLibelle() : null,
                'varietes'  => $varietesData,
                'image'     => $imageUrl->getFileUrl($plante->getImage())
            ];
        }
        return $listPlant;
    }

    #[Route('/ListePlant', name: 'ListePlant', methods: ['GET'])]
    public function afficher_plantes(PlanteRepository $repo, ImageUrlService $imageUrl): JsonResponse
    {
        $plantes = $repo->findAll();
        $result  = $this->formatPlantes($plantes, $imageUrl);
        return new JsonResponse($result, 200);
    }

    #[Route('/ListePlant/{idCat}', name: 'ListePlantFiltre', methods: ['GET'])]
    public function afficher_plantes_filtre(PlanteRepository $repo, int $idCat, ImageUrlService $imageUrl): JsonResponse
    {
        $plantes = $repo->findByCategorie($idCat);
        $result  = $this->formatPlantes($plantes, $imageUrl);
        return new JsonResponse($result, 200);
    }

    #[Route('/catPlant', name: 'ListePlantCat', methods: ['GET'])]
    public function afficher_cat(CategorieRepository $repo): JsonResponse
    {
        $categories = $repo->findAll();
        $listCat = [];

        foreach ($categories as $cat) {
            $listCat[] = [
                'id'      => $cat->getIdCat(),
                'libelle' => $cat->getLibelle()
            ];
        }

        return new JsonResponse($listCat, 200);
    }
}
