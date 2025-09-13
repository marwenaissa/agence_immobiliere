<?php

namespace App\Controller;

use App\Entity\BienImmobilier;
use App\Repository\BienImmobilierRepository;
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

#[Route('/api/bien')]
class BienImmobilierController extends AbstractController
{
    // Liste tous les biens
    #[Route('/', name: 'bien_index', methods: ['GET'])]
    public function index(BienImmobilierRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $biens = $repository->findAll();

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($biens, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
        ]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    // Affiche un bien par id
    #[Route('/{id}', name: 'bien_show', methods: ['GET'])]
    public function show(int $id, BienImmobilierRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $bien = $repository->find($id);
        if (!$bien) {
            return new JsonResponse(['message' => 'Bien non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($bien, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
        ]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    // Création d'un nouveau bien
    #[Route('/', name: 'bien_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $bien = new BienImmobilier();
        $bien->setTitre($data['titre'] ?? '');
        $bien->setDescription($data['description'] ?? '');
        $bien->setSurface($data['surface'] ?? null);
        $bien->setNbreChambres($data['nbreChambres'] ?? null);
        $bien->setAdresse($data['adresse'] ?? '');
        $bien->setStatut($data['statut'] ?? '');
        $bien->setOffreType($data['offreType'] ?? '');
        $bien->setMantant($data['mantant'] ?? 0);

        // Relations : Type, Departement, Proprietaire
        if (!empty($data['type_id'])) {
            $type = $em->getRepository('App\Entity\TypeBien')->find($data['type_id']);
            $bien->setType($type);
        }
        if (!empty($data['departement_id'])) {
            $departement = $em->getRepository('App\Entity\Departement')->find($data['departement_id']);
            $bien->setDepartement($departement);
        }
        if (!empty($data['proprietaire_id'])) {
            $proprietaire = $em->getRepository('App\Entity\Proprietaire')->find($data['proprietaire_id']);
            $bien->setProprietaire($proprietaire);
        }

        $em->persist($bien);
        $em->flush();

        $json = $serializer->serialize($bien, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
        ]);

        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    // Mise à jour d'un bien
    #[Route('/{id}', name: 'bien_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, BienImmobilierRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $bien = $repository->find($id);
        if (!$bien) {
            return new JsonResponse(['message' => 'Bien non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $bien->setTitre($data['titre'] ?? $bien->getTitre());
        $bien->setDescription($data['description'] ?? $bien->getDescription());
        $bien->setSurface($data['surface'] ?? $bien->getSurface());
        $bien->setNbreChambres($data['nbreChambres'] ?? $bien->getNbreChambres());
        $bien->setAdresse($data['adresse'] ?? $bien->getAdresse());
        $bien->setStatut($data['statut'] ?? $bien->getStatut());
        $bien->setOffreType($data['offreType'] ?? $bien->getOffreType());
        $bien->setMantant($data['mantant'] ?? $bien->getMantant());

        // Relations
        if (!empty($data['type_id'])) {
            $type = $em->getRepository('App\Entity\TypeBien')->find($data['type_id']);
            $bien->setType($type);
        }
        if (!empty($data['departement_id'])) {
            $departement = $em->getRepository('App\Entity\Departement')->find($data['departement_id']);
            $bien->setDepartement($departement);
        }
        if (!empty($data['proprietaire_id'])) {
            $proprietaire = $em->getRepository('App\Entity\Proprietaire')->find($data['proprietaire_id']);
            $bien->setProprietaire($proprietaire);
        }

        $em->flush();

        $json = $serializer->serialize($bien, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
        ]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    // Suppression d'un bien
    #[Route('/{id}', name: 'bien_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em, BienImmobilierRepository $repository): JsonResponse
    {
        $bien = $repository->find($id);
        if (!$bien) {
            return new JsonResponse(['message' => 'Bien non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($bien);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
