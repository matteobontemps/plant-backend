<?php

namespace App\Controller\Api;

use App\Entity\Parcelle;
use App\Entity\User;
use App\Entity\Pousse;
use App\Entity\Variete;
use App\Repository\ParcelleRepository;
use App\Repository\VarieteRepository;
use App\Service\ImageUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/varietes')]
class VarieteController extends AbstractController
{
    #[Route('', name: 'api_varietes_list', methods: ['GET'])]
    public function list(VarieteRepository $repo): JsonResponse
    {
        $varietes = $repo->findAll();

        $data = array_map(fn($v) => [
            'idVariete' => $v->getIdVariete(),
            'libelle' => $v->getLibelle(),
            'image' => $v->getImage(),
        ], $varietes);

        return $this->json($data);
    }
}
