<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Repository\DepartementRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

#[Route('/api/departements', name: 'api_departements_')]
class DepartementController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        // Récupérer le repository via l'EntityManager
        $departementRepository = $em->getRepository(\App\Entity\Departement::class);

        // Récupérer tous les départements
        $departements = $departementRepository->findAll();

        // Sérialiser
        $json = $serializer->serialize(
            $departements,
            'json',
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
            ]
        );

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, DepartementRepository $departementRepository, SerializerInterface $serializer): JsonResponse
    {
        $departement = $departementRepository->find($id);
        if (!$departement) {
            return new JsonResponse(['message' => 'Département non trouvé'], 404);
        }

        $json = $serializer->serialize(
            $departement,
            'json',
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
            ]
        );

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, VilleRepository $villeRepository, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $departement = new Departement();
        $departement->setNom($data['nom'] ?? '');

        // Lier une ville si fournie
        if (!empty($data['ville_id'])) {
            $ville = $villeRepository->find($data['ville_id']);
            $departement->setVille($ville);
        }

        $em->persist($departement);
        $em->flush();

        $json = $serializer->serialize(
            $departement,
            'json',
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
            ]
        );

        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, DepartementRepository $departementRepository, EntityManagerInterface $em, VilleRepository $villeRepository, SerializerInterface $serializer): JsonResponse
    {
        $departement = $departementRepository->find($id);
        if (!$departement) {
            return new JsonResponse(['message' => 'Département non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['nom'])) {
            $departement->setNom($data['nom']);
        }

        if (!empty($data['ville_id'])) {
            $ville = $villeRepository->find($data['ville_id']);
            $departement->setVille($ville);
        }

        $em->flush();

        $json = $serializer->serialize(
            $departement,
            'json',
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
            ]
        );

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, DepartementRepository $departementRepository, EntityManagerInterface $em): JsonResponse
    {
        $departement = $departementRepository->find($id);
        if (!$departement) {
            return new JsonResponse(['message' => 'Département non trouvé'], 404);
        }

        $em->remove($departement);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
