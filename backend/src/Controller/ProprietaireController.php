<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/proprietaires')]
class ProprietaireController extends AbstractController
{
    #[Route('', name: 'get_proprietaires', methods: ['GET'])]
    public function getProprietaires(EntityManagerInterface $em): JsonResponse
    {
        $proprietaires = $em->getRepository(Proprietaire::class)->findAll();

        $data = array_map(function(Proprietaire $p) {
            $utilisateur = $p->getUtilisateur();
            return [
                'id' => $p->getId(),
                'nom' => $utilisateur?->getNom(),
                'prenom' => $utilisateur?->getPrenom(),
                'email' => $utilisateur?->getEmail(),
                'cin' => $utilisateur?->getCin(),
                'telephone' => $utilisateur?->getTelephone(),
                'profession' => $p->getProfession(),
            ];
        }, $proprietaires);

        return $this->json($data);
    }

    #[Route('', name: 'create_proprietaire', methods: ['POST'])]
    public function createProprietaire(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $proprietaire = new Proprietaire();
        $proprietaire->setProfession($data['profession'] ?? null);

        // Associer avec utilisateur (à adapter selon ton besoin)
        // $utilisateur = $em->getRepository(Utilisateur::class)->find($data['utilisateurId']);
        // $proprietaire->setUtilisateur($utilisateur);

        $em->persist($proprietaire);
        $em->flush();

        return $this->json(['message' => 'Propriétaire créé avec succès', 'id' => $proprietaire->getId()]);
    }

    #[Route('/{id}', name: 'update_proprietaire', methods: ['PUT'])]
    public function updateProprietaire(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $proprietaire = $em->getRepository(Proprietaire::class)->find($id);
        if (!$proprietaire) {
            return $this->json(['message' => 'Propriétaire introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $proprietaire->setProfession($data['profession'] ?? $proprietaire->getProfession());

        $em->flush();

        return $this->json(['message' => 'Propriétaire mis à jour']);
    }

    #[Route('/{id}', name: 'delete_proprietaire', methods: ['DELETE'])]
    public function deleteProprietaire(int $id, EntityManagerInterface $em): JsonResponse
    {
        $proprietaire = $em->getRepository(Proprietaire::class)->find($id);
        if (!$proprietaire) {
            return $this->json(['message' => 'Propriétaire introuvable'], 404);
        }

        $em->remove($proprietaire);
        $em->flush();

        return $this->json(['message' => 'Propriétaire supprimé avec succès']);
    }
}
