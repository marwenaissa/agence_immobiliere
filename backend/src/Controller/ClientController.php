<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Entity\Client;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/clients', name: 'api_clients_')]
class ClientController extends AbstractController
{
    private ClientRepository $clientRepository;
    private EntityManagerInterface $em;

    public function __construct(ClientRepository $clientRepository, EntityManagerInterface $em)
    {
        $this->clientRepository = $clientRepository;
        $this->em = $em;
    }

    // ✅ Liste des clients
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $clients = $this->clientRepository->findAll();
        $data = [];

        foreach ($clients as $client) {
            $data[] = [
                'id' => $client->getId(),
                'nomUtilisateur' => $client->getUtilisateur()->getNom(),
                'prenomUtilisateur' => $client->getUtilisateur()->getPrenom(),
                'profession' => $client->getProfession(),
                'passeport' => $client->getPasseport(),
            ];
        }

        return $this->json($data);
    }

    // ✅ Récupérer un client par ID
    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function getClient(int $id): JsonResponse
    {
        $client = $this->clientRepository->find($id);

        if (!$client) {
            return $this->json(['message' => 'Client non trouvé'], 404);
        }

        $data = [
            'id' => $client->getId(),
            'nomUtilisateur' => $client->getUtilisateur()->getNom(),
            'prenomUtilisateur' => $client->getUtilisateur()->getPrenom(),
            'profession' => $client->getProfession(),
            'passeport' => $client->getPasseport(),
        ];

        return $this->json($data);
    }

    // ✅ Créer un client
    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['utilisateur_id'])) {
            return $this->json(['message' => 'Données invalides'], 400);
        }

        $utilisateur = $this->em->getRepository(Utilisateur::class)->find($data['utilisateur_id']);
        if (!$utilisateur) {
            return $this->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $client = new Client();
        $client->setUtilisateur($utilisateur);
        $client->setProfession($data['profession'] ?? '');
        $client->setPasseport($data['passeport'] ?? '');

        $this->em->persist($client);
        $this->em->flush();

        return $this->json([
            'id' => $client->getId(),
            'nomUtilisateur' => $utilisateur->getNom(),
            'prenomUtilisateur' => $utilisateur->getPrenom(),
            'profession' => $client->getProfession(),
            'passeport' => $client->getPasseport(),
        ], 201);
    }

    // ✅ Mettre à jour un client
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $client = $this->clientRepository->find($id);
        if (!$client) {
            return $this->json(['message' => 'Client non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['message' => 'Données invalides'], 400);
        }

        if (isset($data['profession'])) {
            $client->setProfession($data['profession']);
        }
        if (isset($data['passeport'])) {
            $client->setPasseport($data['passeport']);
        }

        $this->em->flush();

        return $this->json([
            'id' => $client->getId(),
            'nomUtilisateur' => $client->getUtilisateur()->getNom(),
            'prenomUtilisateur' => $client->getUtilisateur()->getPrenom(),
            'profession' => $client->getProfession(),
            'passeport' => $client->getPasseport(),
        ]);
    }

    // ✅ Supprimer un client
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $client = $this->clientRepository->find($id);
        if (!$client) {
            return $this->json(['message' => 'Client non trouvé'], 404);
        }

        $this->em->remove($client);
        $this->em->flush();

        return $this->json(['message' => 'Client supprimé']);
    }
}

