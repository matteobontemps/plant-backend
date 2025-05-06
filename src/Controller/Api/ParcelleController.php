<?php

namespace App\Controller\Api;

use App\Entity\Parcelle;
use App\Entity\User;
use App\Entity\Pousse;
use App\Repository\ParcelleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ParcelleController extends AbstractController
{
    #[Route('/api/parcelles', name: 'api_parcelles_list', methods: ['GET'])]
    public function list(ParcelleRepository $repo): JsonResponse
    {
        $parcelles = $repo->findAll();
        $data = array_map(fn($p) => [
            'idParcelle' => $p->getIdParcelle(),
            'libelle' => $p->getLibelle(),
            'longueur' => $p->getLongueur(),
            'largeur' => $p->getLargeur(),
            'taille_carres' => $p->getTailleCarres(),
            'idUser' => $p->getIdUser()->getIdUser(), // ou autre selon relation
            'pousses' => array_map(fn($pp) => [
                'idPousse' => $pp->getIdPousse(),
                'nbPlants' => $pp->getNbPlants(),
                'datPlantation' => $pp->getDatePlantation(),
                'idVariete' => $pp->getIdVariete()->getIdVariete(),
            ],$p->getPousses()->toArray()),
        ], $parcelles);

        return $this->json($data);
    }

    #[Route('/api/parcelles', name: 'api_parcelles_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $parcelle = new Parcelle();
        $parcelle->setIdParcelle(uniqid());
        $parcelle->setLibelle($data['libelle']);
        $parcelle->setLongueur($data['longueur']);
        $parcelle->setLargeur($data['largeur']);
        $parcelle->setTailleCarres($data['taille_carres']);

        // TODO : récupérer l'utilisateur connecté ou passer l'id en POST
        // Exemple avec un User fictif :
        // $user = $em->getRepository(\App\Entity\User::class)->find($data['idUser']);
        // if (!$user) return $this->json(['error' => 'Utilisateur non trouvé'], 404);

        $parcelle->setIdUser($em->getRepository(User::class)->find('U001'));

        $em->persist($parcelle);
        $em->flush();

        return $this->json(['message' => 'Parcelle ajoutée']);
    }
}
