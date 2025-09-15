<?php
namespace App\Controller;

use App\Entity\BienImmobilier;
use App\Entity\Visite;
use App\Entity\Visiteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class VisiteController extends AbstractController
{
    #[Route('/biens/{id}/visites', name:'add_visite', methods:['POST'])]
    public function addVisite(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $bien = $em->getRepository(BienImmobilier::class)->find($id);
        if (!$bien) return $this->json(['error'=>'Bien non trouvé'],404);

        $data = json_decode($request->getContent(), true);

        $visite = new Visite();
        $visite->setBien($bien);

        // Relation avec Visiteur si fourni
        if (!empty($data['visiteurId'])) {
            $visiteur = $em->getRepository(Visiteur::class)->find($data['visiteurId']);
            if ($visiteur) {
                $visite->setRelation($visiteur);
            }
        }

        if (!empty($data['dateProgrammee'])) {
            $visite->setDateProgrammee(new \DateTime($data['dateProgrammee']));
        }

        // Optionnel : dateReelle au moment de la visite, ou à null
        if (!empty($data['dateReelle'])) {
            $visite->setDateReelle(new \DateTime($data['dateReelle']));
        }

        $visite->setStatut($data['statut'] ?? 'programmee');
        $visite->setCommentaire($data['commentaire'] ?? null);

        $em->persist($visite);
        $em->flush();

        return $this->json([
            'id' => $visite->getId(),
            'bienId' => $bien->getId(),
            'visiteurId' => $visite->getRelation()?->getId(),
            'dateProgrammee' => $visite->getDateProgrammee()?->format('Y-m-d\TH:i'),
            'dateReelle' => $visite->getDateReelle()?->format('Y-m-d\TH:i'),
            'statut' => $visite->getStatut(),
            'commentaire' => $visite->getCommentaire()
        ], 201);
    }

    #[Route('/biens/{id}/visites', name:'get_visites', methods:['GET'])]
    public function getVisites(int $id, EntityManagerInterface $em): JsonResponse
    {
        $bien = $em->getRepository(BienImmobilier::class)->find($id);
        if (!$bien) return $this->json(['error'=>'Bien non trouvé'],404);

        $visites = $bien->getVisites()->map(fn($v)=>[
            'id' => $v->getId(),
            'visiteurId' => $v->getRelation()?->getId(),
            'dateProgrammee' => $v->getDateProgrammee()?->format('Y-m-d\TH:i'),
            'dateReelle' => $v->getDateReelle()?->format('Y-m-d\TH:i'),
            'statut' => $v->getStatut(),
            'commentaire' => $v->getCommentaire(),
        ]);

        return $this->json($visites);
    }

    #[Route('/biens/{bienId}/visites/{visiteId}', name:'update_visite', methods:['PUT'])]
    public function updateVisite(int $bienId, int $visiteId, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $visite = $em->getRepository(Visite::class)->find($visiteId);
        if (!$visite) return $this->json(['error'=>'Visite non trouvée'],404);

        $data = json_decode($request->getContent(), true);

        if (isset($data['statut'])) {
            $visite->setStatut($data['statut']);
        }

        $em->flush();

        return $this->json([
            'id' => $visite->getId(),
            'statut' => $visite->getStatut()
        ]);
    }


}
