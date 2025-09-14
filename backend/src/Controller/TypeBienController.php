<?php

namespace App\Controller;

use App\Entity\TypeBien;
use App\Repository\TypeBienRepository;
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

#[Route('/api/types')]
class TypeBienController extends AbstractController
{
    // Liste tous les types de bien
    // src/Controller/TypeBienController.php
    #[Route('', name: 'api_types', methods: ['GET'])]
    public function getTypes(EntityManagerInterface $em): JsonResponse {
        $types = $em->getRepository(TypeBien::class)->findAll();

        // Retourner juste id et libelle
        $data = array_map(fn($t) => [
            'id' => $t->getId(),
            'libelle' => $t->getLibelle()
        ], $types);

        return $this->json($data);
    }


    // Affiche un type de bien par id
    #[Route('/{id}', name: 'typebien_show', methods: ['GET'])]
    public function show(int $id, TypeBienRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $type = $repository->find($id);
        if (!$type) {
            return new JsonResponse(['message' => 'Type non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($type, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
        ]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    // Création d'un nouveau type de bien
    #[Route('/', name: 'typebien_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $type = new TypeBien();
        $type->setLibelle($data['libelle'] ?? '');

        $em->persist($type);
        $em->flush();

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($type, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
        ]);

        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    // Mise à jour d'un type de bien
    #[Route('/{id}', name: 'typebien_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, TypeBienRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $type = $repository->find($id);
        if (!$type) {
            return new JsonResponse(['message' => 'Type non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $type->setLibelle($data['libelle'] ?? $type->getLibelle());

        $em->flush();

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($type, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
        ]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    // Suppression d'un type de bien
    #[Route('/{id}', name: 'typebien_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em, TypeBienRepository $repository): JsonResponse
    {
        $type = $repository->find($id);
        if (!$type) {
            return new JsonResponse(['message' => 'Type non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($type);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
