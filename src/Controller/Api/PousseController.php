<?php

namespace App\Controller\Api;

use App\Entity\Parcelle;
use App\Entity\User;
use App\Entity\Pousse;
use App\Entity\Variete;
use App\Repository\ParcelleRepository;
use App\Service\ImageUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PousseController extends AbstractController
{
    #[Route('/api/pousses', name: 'api_pousses_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $pousse = new Pousse();
        $pousse->setIdPousse(uniqid());
        $pousse->setX($data['x']);
        $pousse->setY($data['y']);
        $pousse->setNbPlants($data['nbPlants']);
        $pousse->setDatePlantation(new \DateTime($data['datePlantation']));

        $parcelle = $em->getRepository(Parcelle::class)->find($data['idParcelle']);
        $variete = $em->getRepository(Variete::class)->find($data['idVariete']);
        if (!$parcelle || !$variete) {
            return $this->json(['error' => 'Parcelle ou variété introuvable'], 400);
        }
        $pousse->setIdParcelle($parcelle);
        $pousse->setIdVariete($variete);

        $em->persist($pousse);
        $em->flush();

        return $this->json(['message' => 'Pousse ajoutée']);
    }

    #[Route('/api/pousses/{x}/{y}/{idParcelle}', name: 'api_pousse_delete', methods: ['DELETE'])]
    public function deletePousse(int $x, int $y, string $idParcelle, EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(Pousse::class);
        $pousse = $repo->findOneBy([
            'x' => $x,
            'y' => $y,
            'idParcelle' => $idParcelle
        ]);

        if (!$pousse) {
            return $this->json(['error' => 'Pousse non trouvée'], 404);
        }

        $em->remove($pousse);
        $em->flush();

        return $this->json(['message' => 'Pousse supprimée']);
    }
}