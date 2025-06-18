<?php

namespace App\Controller\Api;

use App\Entity\Plante;
use App\Entity\Variete;
use App\Repository\PlanteRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PlanteController extends AbstractController
{
    #[Route('/api/plantes', name: 'api_plantes_add', methods: ['POST'])]
    public function addPlante(Request $request, EntityManagerInterface $em, CategorieRepository $repo): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $plante = new Plante();
        $plante->setIdPlante(uniqid());
        $plante->setNom($data['nom']);
        if (!empty($data['description'])) {
            $plante->setDescription($data['description']);
        }
        if (!empty($data['idCat'])) {
            $cat = $repo->find($data['idCat']);
            if ($cat) {
                $plante->setIdCat($cat);
            }
        }
        $em->persist($plante);
        $em->flush();
        return $this->json(['message' => 'Plante ajoutée']);
    }

    #[Route('/api/plantes', name: 'api_plantes_list', methods: ['GET'])]
    public function listPlantes(PlanteRepository $repo): JsonResponse
    {
        $plantes = $repo->findAll();

        $data = array_map(fn($v) => [
            'idPlante' => $v->getIdPlante(),
            'nom' => $v->getNom(),
            'description' => $v->getDescription(),
            'categorie' => $v->getIdCat() ? [
                'idCat' => $v->getIdCat()->getIdCat(),
                'libelleCat' => $v->getIdCat()->getLibelle()
            ] : null,
        ], $plantes);

        return $this->json($data);
    }
}