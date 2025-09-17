<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/api/clients', name: 'get_clients', methods: ['GET'])]
    public function getClients(EntityManagerInterface $em): JsonResponse
    {
        $clients = $em->getRepository(Client::class)->findAll();

        $data = array_map(function(Client $c) {
            $utilisateur = $c->getUtilisateur();
            return [
                'id' => $c->getId(),
                'nom' => $utilisateur?->getNom(),
                'prenom' => $utilisateur?->getPrenom(),
                'email' => $utilisateur?->getEmail(),
                'cin' => $utilisateur?->getCin(),
                'telephone' => $utilisateur?->getTelephone(),
                'profession' => $c->getProfession(),
                'passeport' => $c->getPasseport(),
            ];
        }, $clients);

        return $this->json($data);
    }

    #[Route('/api/clients', name: 'create_client', methods: ['POST'])]
    public function createClient(EntityManagerInterface $em): JsonResponse
    {
        // ici tu gèreras la création comme tu l’as fait pour Visiteur
        return $this->json(['message' => 'Création client OK']);
    }

    #[Route('/api/clients/{id}', name: 'update_client', methods: ['PUT'])]
    public function updateClient(int $id, EntityManagerInterface $em): JsonResponse
    {
        // mise à jour du client
        return $this->json(['message' => "Mise à jour client $id OK"]);
    }

    #[Route('/api/clients/{id}', name: 'delete_client', methods: ['DELETE'])]
    public function deleteClient(int $id, EntityManagerInterface $em): JsonResponse
    {
        // suppression client
        return $this->json(['message' => "Suppression client $id OK"]);
    }
}
