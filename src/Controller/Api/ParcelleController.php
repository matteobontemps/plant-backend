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
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class ParcelleController extends AbstractController
{
    #[Route('/api/parcelles', name: 'api_parcelles_list', methods: ['GET'])]
    public function list(ParcelleRepository $repo, ImageUrlService $imageUrlService, SessionInterface $session): JsonResponse
    {
        if (!$session->has('user_id')) {
            return new JsonResponse(['error' => 'Acces non autorise.'], 401);
        }
        $userId = $session->get('user_id');
        $parcelles = $repo->findBy(['idUser' => $userId]);
        $data = array_map(fn($p) => [
            'url' => $imageUrlService->getFileUrl('pexels-karolina-grabowska-4022188.jpg'),
            'idParcelle' => $p->getIdParcelle(),
            'libelle' => $p->getLibelle(),
            'longueur' => $p->getLongueur(),
            'largeur' => $p->getLargeur(),
            'taille_carres' => $p->getTailleCarres(),
            'idUser' => $p->getIdUser()->getIdUser(),
            'pousses' => array_map(fn($pp) => [
                'idPousse' => $pp->getIdPousse(),
                'x' => $pp->getX(),
                'y' => $pp->getY(),
                'nbPlants' => $pp->getNbPlants(),
                'datePlantation' => $pp->getDatePlantation(),
                'variete' => [
                    'idVariete' => $pp->getIdVariete()->getIdVariete(),
                    'libelle' => $pp->getIdVariete()->getLibelle(),
                    'description' => $pp->getIdVariete()->getDescription(),
                    'nbGraines' => $pp->getIdVariete()->getNbGraines(),
                    'ensoleillement' => $pp->getIdVariete()->getEnsoleillement(),
                    'frequence_arrosage' => $pp->getIdVariete()->getFrequenceArrosage(),
                    'date_debut_periode_plantation' => $pp->getIdVariete()->getDateDebutPeriodePlantation(),
                    'date_fin_periode_plantation' => $pp->getIdVariete()->getDateFinPeriodePlantation(),
                    'resistance_froid' => $pp->getIdVariete()->getResistanceFroid(),
                    'temps_avant_recolte' => $pp->getIdVariete()->getTempsAvantRecolte(),
                    'ph' => $pp->getIdVariete()->getPh(),
                    'image' => $pp->getIdVariete()->getImage(),
                    'idPlante' => $pp->getIdVariete()->getIdPlante()->getIdPlante()
                ],
            ],$p->getPousses()->toArray()),
        ], $parcelles);

        return $this->json($data);
    }

    #[Route('/api/parcelles', name: 'api_parcelles_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SessionInterface $session): JsonResponse
    {
         if (!$session->has('user_id')) {
            return new JsonResponse(['error' => 'Acces non autorise.'], 401);
        }
        $userId = $session->get('user_id');
        $user = $em->getRepository(User::class)->find($userId);
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 404);
        }
        $data = json_decode($request->getContent(), true);

        $parcelle = new Parcelle();
        $parcelle->setIdParcelle(uniqid());
        $parcelle->setLibelle($data['libelle']);
        $parcelle->setLongueur($data['longueur']);
        $parcelle->setLargeur($data['largeur']);
        $parcelle->setTailleCarres($data['taille_carres']);
        $parcelle->setIdUser($user);
        // TODO : récupérer l'utilisateur connecté ou passer l'id en POST
        // Exemple avec un User fictif :
        // $user = $em->getRepository(\App\Entity\User::class)->find($data['idUser']);
        // if (!$user) return $this->json(['error' => 'Utilisateur non trouvé'], 404);


        $em->persist($parcelle);
        $em->flush();

        return $this->json(['message' => 'Parcelle ajoutée']);
    }
    #[Route('/api/parcelles/simple', name: 'api_parcelles_simple', methods: ['GET'])]
public function listSimple(
    ParcelleRepository $repo,
    SessionInterface $session
): JsonResponse {
    if (!$session->has('user_id')) {
        return new JsonResponse(['error' => 'Acces non autorise.'], 401);
    }

    $userId = $session->get('user_id');
    $parcelles = $repo->findBy(['idUser' => $userId]);

    $data = array_map(fn($p) => [
        'idParcelle' => $p->getIdParcelle(),
        'libelle' => $p->getLibelle(),
    ], $parcelles);

    return $this->json($data);
}

}
