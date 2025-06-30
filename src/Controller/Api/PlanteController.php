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
    #[Route('/api/plantes/{id}', name: 'api_plantes_get', methods: ['GET'])]
    public function getPlante(string $id, PlanteRepository $repo): JsonResponse
    {
        $plante = $repo->findOneBy(['idPlante' => $id]);

        if (!$plante) {
            return $this->json(['message' => 'Plante non trouvée'], 404);
        }

        $data = [
            'idPlante' => $plante->getIdPlante(),
            'nom' => $plante->getNom(),
            'description' => $plante->getDescription(),
            'categorie' => $plante->getIdCat() ? [
                'idCat' => $plante->getIdCat()->getIdCat(),
                'libelleCat' => $plante->getIdCat()->getLibelle()
            ] : null,
            'varietes' => array_map(fn(Variete $v) => [
                'idVariete' => $v->getIdVariete(),
                'libelle' => $v->getLibelle(),
                'description' => $v->getDescription(),
            ], $plante->getVarietes()->toArray()),
        ];

        return $this->json($data);
    }
        
}