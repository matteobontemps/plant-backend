<?php

namespace App\Controller\Api;

use App\Entity\Parcelle;
use App\Entity\User;
use App\Entity\Pousse;
use App\Entity\Variete;
use App\Repository\ParcelleRepository;
use App\Repository\VarieteRepository;
use App\Repository\PlanteRepository;
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
            'description' => $v->getDescription(),
            'nbGraines' => $v->getNbGraines(),
            'ensoleillement' => $v->getEnsoleillement(),
            'frequence_arrosage' => $v->getFrequenceArrosage(),
            'date_debut_periode_plantation' => $v->getDateDebutPeriodePlantation(),
            'date_fin_periode_plantation' => $v->getDateFinPeriodePlantation(),
            'resistance_froid' => $v->getResistanceFroid(),
            'temps_avant_recolte' => $v->getTempsAvantRecolte(),
            'ph' => $v->getPh(),
            'image' => $v->getImage(),
            'idPlante' => $v->getIdPlante()->getIdPlante()
        ], $varietes);

        return $this->json($data);
    }

    #[Route('', name: 'api_varietes_add', methods: ['POST'])]
public function addVariete(Request $request, EntityManagerInterface $em, PlanteRepository $planteRepo): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (!$data || empty($data['libelle']) || empty($data['idPlante'])) {
        return new JsonResponse(['message' => 'Données incomplètes'], 400);
    }

    $plante = $planteRepo->find($data['idPlante']);
    if (!$plante) {
        return new JsonResponse(['message' => 'Plante introuvable'], 404);
    }

    $variete = new Variete();
    $variete->setIdVariete(uniqid());
    $variete->setLibelle($data['libelle']);
    $variete->setIdPlante($plante);

    $variete->setDescription($data['description'] ?? null);
    $variete->setNbGraines($data['nbGraines'] ?? null);
    $variete->setEnsoleillement($data['ensoleillement'] ?? null);
    $variete->setFrequenceArrosage($data['frequence_arrosage'] ?? null);

    if (!empty($data['date_debut_periode_plantation'])) {
        try {
            $variete->setDateDebutPeriodePlantation(new \DateTime($data['date_debut_periode_plantation']));
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Format date_debut_periode_plantation invalide'], 400);
        }
    }

    if (!empty($data['date_fin_periode_plantation'])) {
        try {
            $variete->setDateFinPeriodePlantation(new \DateTime($data['date_fin_periode_plantation']));
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Format date_fin_periode_plantation invalide'], 400);
        }
    }

    $variete->setResistanceFroid($data['resistance_froid'] ?? null);
    $variete->setTempsAvantRecolte($data['temps_avant_recolte'] ?? null);
    $variete->setPh($data['ph'] ?? null);
    $variete->setImage($data['image'] ?? null);

    $em->persist($variete);
    $em->flush();

    return new JsonResponse(['message' => 'Variété ajoutée avec succès'], 201);
}
}
