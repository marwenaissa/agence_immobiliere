<?php


namespace App\Controller;

use App\Entity\BienImmobilier;
use App\Entity\PieceJointe;


use App\Repository\OperationBienRepository;
use App\Repository\ClientRepository;
use App\Repository\ProprietaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\Client;
use App\Entity\Proprietaire;
use App\Entity\OperationBien;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/api/operations')]
class OperationBienController extends AbstractController
{
    #[Route('', name: 'operation_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Champs obligatoires
        $requiredFields = ['type', 'bien_id', 'montant', 'statut', 'dateOperation' , 'commentaire'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Le champ '$field' est obligatoire."], 400);
            }
        }

        // Récupération du bien
        $bien = $em->getRepository(BienImmobilier::class)->find($data['bien_id']);
        if (!$bien) {
            return $this->json(['error' => 'Bien non trouvé.'], 400);
        }

        $operation = new OperationBien();
        $operation->setType($data['type']);
        $operation->setBien($bien);
        $operation->setMontant($data['montant']);
        $operation->setStatut($data['statut']);
        $operation->setDateOperation(new \DateTimeImmutable($data['dateOperation']));
        $operation->setCommentaire($data['commentaire']);

        // Relation client (optionnelle)
        if (!empty($data['client_id'])) {
            $client = $em->getRepository(Client::class)->find($data['client_id']);
            if ($client) {
                $operation->setClient($client);
                $client->addOperationBien($operation); // bidirectionnel
            }
        }

        // Relation propriétaire (optionnelle)
        if (!empty($data['proprietaire_id'])) {
            $proprietaire = $em->getRepository(Proprietaire::class)->find($data['proprietaire_id']);
            if ($proprietaire) {
                $operation->setProprietaire($proprietaire);
                // si tu veux, tu peux mettre à jour les biens du propriétaire ici
            }
        }

        // Si location, on peut gérer dateDebut / dateFin
        if (!empty($data['dateDebut'])) {
            $operation->setDateDebut(new \DateTimeImmutable($data['dateDebut']));
        }
        if (!empty($data['dateFin'])) {
            $operation->setDateFin(new \DateTimeImmutable($data['dateFin']));
        }

        $em->persist($operation);
        $em->flush();

        return $this->json($operation, 201, [], ['groups' => 'operation:read']);
    }


    #[Route('', name: 'operation_list', methods: ['GET'])]
    public function list(OperationBienRepository $repository): JsonResponse
    {
        $operations = $repository->findAll();

        // On transforme les entités en tableau simple avec les infos désirées
        $data = array_map(function($op) {
            return [
                'id' => $op->getId(),
                'type' => $op->getType(),
                'dateOperation' => $op->getDateOperation()?->format('Y-m-d H:i:s'),
                'montant' => $op->getMontant(),
                'statut' => $op->getStatut(),
                'commentaire' => $op->getCommentaire(),

                'bien' => $op->getBien() ? [
                    'id' => $op->getBien()->getId(),
                    'titre' => $op->getBien()->getTitre() ?: $op->getBien()->getNom()
                ] : null,

                'client' => $op->getClient() ? [
                    'id' => $op->getClient()->getId(),
                    'nom' => $op->getClient()->getUtilisateur()?->getNom(),
                    'prenom' => $op->getClient()->getUtilisateur()?->getPrenom()
                ] : null,

                'proprietaire' => $op->getProprietaire() ? [
                    'id' => $op->getProprietaire()->getId(),
                    'nom' => $op->getProprietaire()->getUtilisateur()?->getNom(),
                    'prenom' => $op->getProprietaire()->getUtilisateur()?->getPrenom()
                ] : null,
            ];
        }, $operations);

        return new JsonResponse($data, Response::HTTP_OK);
    }



    #[Route('/{id}', name: 'operation_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em, OperationBienRepository $repository): JsonResponse
    {
        $op = $repository->find($id);
        if (!$op) return $this->json(['message' => 'Operation non trouvée'], 404);
        $em->remove($op);
        $em->flush();
        return new JsonResponse(null, 204);
    }
}
