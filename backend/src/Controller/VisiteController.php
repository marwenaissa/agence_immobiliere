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
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

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

       

        $visite->setStatut($data['statut'] ?? 'programmee');
        $visite->setCommentaire($data['commentaire'] ?? null);

        $em->persist($visite);
        $em->flush();

        return $this->json([
            'id' => $visite->getId(),
            'bienId' => $bien->getId(),
            'visiteurId' => $visite->getRelation()?->getId(),
            'dateProgrammee' => $visite->getDateProgrammee()?->format('Y-m-d\TH:i'),
            'statut' => $visite->getStatut(),
            'commentaire' => $visite->getCommentaire()
        ], 201);
    }

    #[Route('/visiteurs', name: 'get_visiteurs', methods: ['GET'])]
    public function getVisiteurs(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $page = max((int) $request->query->get('page', 1), 1);
        $limit = max((int) $request->query->get('limit', 10), 1);
        $offset = ($page - 1) * $limit;

        $visiteurRepo = $em->getRepository(Visiteur::class);

        $total = count($visiteurRepo->findAll());

        $visiteurs = $visiteurRepo->findBy([], null, $limit, $offset);

        $data = array_map(function(Visiteur $v) {
            $utilisateur = $v->getUtilisateur();
            return [
                'id' => $v->getId(),
                'nom' => $utilisateur?->getNom(),
                'prenom' => $utilisateur?->getPrenom(),
                'email' => $utilisateur?->getEmail(),
                'cin' => $utilisateur?->getCin(),
                'telephone' => $utilisateur?->getTelephone(),
                'profession' => $v->getProfession(),
            ];
        }, $visiteurs);

        return $this->json([
            'data' => $data,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'totalPages' => ceil($total / $limit),
        ]);
    }


    #[Route('/bien/{id}', name:'get_visites', methods:['GET'])]
    public function getVisites(int $id, EntityManagerInterface $em): JsonResponse
    {
        $bien = $em->getRepository(BienImmobilier::class)->find($id);
        if (!$bien) {
            return $this->json(['error'=>'Bien non trouvé'],404);
        }

        $visites = $bien->getVisites()->map(function($v) {
            $utilisateur = $v->getRelation()?->getUtilisateur(); // 👈 lien vers Utilisateur
            return [
                'id' => $v->getId(),
                'visiteurId' => $v->getRelation()?->getId(),
                'visiteur' => $utilisateur ? [
                    'prenom' => $utilisateur->getPrenom(),
                    'nom' => $utilisateur->getNom()
                ] : null,
                'dateProgrammee' => $v->getDateProgrammee()?->format('Y-m-d\TH:i'),
                'statut' => $v->getStatut(),
                'commentaire' => $v->getCommentaire(),
            ];
        });

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

        
    #[Route('/visites/add', name: 'app_visite_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em, HubInterface $hub): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $visite = new Visite();

        // Hydrater correctement
        $visite->setBien($em->getReference(BienImmobilier::class, $data['bienId']));
        $visite->setRelation($em->getReference(Visiteur::class, $data['visiteurId']));
        
        // ⚡ Convertir la chaîne en DateTime
        $visite->setDateProgrammee(new \DateTime($data['dateProgrammee']));

        $visite->setStatut($data['statut'] ?? 'programmee');
        $visite->setCommentaire($data['commentaire'] ?? null);

        $em->persist($visite);
        $em->flush();

        // Event Mercure
        $update = new Update(
            'visites',
            json_encode([
                'id' => $visite->getId(),
                'bien' => $visite->getBien()->getId(),
                'visiteur' => $visite->getRelation()->getId(),
                'date' => $visite->getDateProgrammee()->format('Y-m-d H:i'),
                'statut' => $visite->getStatut(),
            ])
        );
        $hub->publish($update);

        return $this->json(['status' => 'Visite ajoutée']);
    }



}
