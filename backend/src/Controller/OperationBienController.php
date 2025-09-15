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
        $requiredFields = ['type', 'bien_id', 'montant', 'statut', 'dateOperation'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Le champ '$field' est obligatoire."], 400);
            }
        }

        $bien = $em->getRepository(BienImmobilier::class)->find($data['bien_id']);
        if (!$bien) return $this->json(['error' => 'Bien non trouvé.'], 400);

        $operation = new OperationBien();
        $operation->setType($data['type']);
        $operation->setBien($bien);
        $operation->setMontant($data['montant']);
        $operation->setStatut($data['statut']);
        $operation->setDateOperation(new \DateTimeImmutable($data['dateOperation']));

        // Relations optionnelles : vendeur/acheteur, locataire/bailleur
        if (!empty($data['acheteur_id'])) {
            $operation->setAcheteur($em->getRepository(Client::class)->find($data['acheteur_id']));
        }
        if (!empty($data['vendeur_id'])) {
            $operation->setVendeur($em->getRepository(Proprietaire::class)->find($data['vendeur_id']));
        }
        if (!empty($data['locataire_id'])) {
            $operation->setLocataire($em->getRepository(Client::class)->find($data['locataire_id']));
        }
        if (!empty($data['bailleur_id'])) {
            $operation->setBailleur($em->getRepository(Proprietaire::class)->find($data['bailleur_id']));
        }

        $em->persist($operation);
        $em->flush();

        return $this->json($operation, 201, [], ['groups' => 'operation:read']);
    }

    #[Route('', name: 'operation_list', methods: ['GET'])]
    public function list(OperationBienRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $operations = $repository->findAll();
        $json = $serializer->serialize($operations, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($obj) => $obj->getId(),
        ]);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
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
