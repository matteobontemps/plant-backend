<?php

namespace App\Controller\Api;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class CategorieController extends AbstractController
{
    #[Route('/api/categories', name: 'api_categories_list', methods: ['GET'])]
    public function list(CategorieRepository $repo): JsonResponse
    {
        $categories = $repo->findAll();

        $data = array_map(fn($v) => [
            'idCat' => $v->getIdCat(),
            'libelle' => $v->getLibelle(),
            'CatParent' => $v->getIdCatParent() ? [
                'idCatParent' => $v->getIdCatParent()->getIdCat(),
                'libelleCatParent' => $v->getIdCatParent()->getLibelle()
            ] : null,
        ], $categories);

        return $this->json($data);
    }

    #[Route('/api/categories', name: 'api_categories_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, CategorieRepository $repo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['libelle'])) {
            return $this->json(['error' => 'Le libellé est requis.'], 400);
        }

        $categorie = new Categorie();
        $categorie->setIdCat(uniqid());
        $categorie->setLibelle($data['libelle']);

        // Si une catégorie parente est fournie :
        if (!empty($data['idCatParent'])) {
            $parent = $repo->find($data['idCatParent']);
            if ($parent) {
                $categorie->setIdCatParent($parent);
            }
        }

        $em->persist($categorie);
        $em->flush();

        return $this->json(['message' => 'Catégorie créée avec succès.'], 201);
    }
}
