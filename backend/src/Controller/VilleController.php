<?php

namespace App\Controller;

use App\Entity\Ville;
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



#[Route('/api/villes', name: 'api_villes_')]
class VilleController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(VilleRepository $villeRepository, SerializerInterface $serializer): JsonResponse
    {
        // Récupération de toutes les villes
        $villes = $villeRepository->findAll();

        // Sérialisation en JSON
        $json = $serializer->serialize(
            $villes,
            'json',
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
            ]
        );

        // Retour de la réponse JSON
        return new JsonResponse($json, 200, [], true);
    } 

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, VilleRepository $villeRepository, SerializerInterface $serializer): JsonResponse
    {
        $ville = $villeRepository->find($id);
        if (!$ville) {
            return new JsonResponse(['message' => 'Ville non trouvée'], 404);
        }

        $json = $serializer->serialize(
            $ville,
            'json',
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
            ]
        );

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ): JsonResponse {
        // Récupération des données envoyées
        $data = json_decode($request->getContent(), true);

        // Création d'une nouvelle ville
        $ville = new Ville();
        $ville->setNom($data['nom'] ?? '');

        // Persistance en base
        $em->persist($ville);
        $em->flush();

        // Sérialisation en JSON
        $json = $serializer->serialize(
            $ville,
            'json',
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
            ]
        );

        return new JsonResponse($json, 201, [], true);
    }


   #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(
        int $id,
        Request $request,
        VilleRepository $villeRepository,
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ): JsonResponse {
        // Récupérer la ville par son ID
        $ville = $villeRepository->find($id);
        if (!$ville) {
            return new JsonResponse(['message' => 'Ville non trouvée'], 404);
        }

        // Récupérer les données du corps de la requête
        $data = json_decode($request->getContent(), true);

        // Mettre à jour le nom si fourni
        if (!empty($data['nom'])) {
            $ville->setNom($data['nom']);
        }

        // Sauvegarder les modifications
        $em->flush();

        // Sérialiser la réponse JSON
        $json = $serializer->serialize(
            $ville,
            'json',
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
            ]
        );

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        VilleRepository $villeRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $ville = $villeRepository->find($id);
        if (!$ville) {
            return new JsonResponse(['message' => 'Ville non trouvée'], 404);
        }

        // Vérifier les départements liés
        if (count($ville->getDepartements()) > 0) {
            return new JsonResponse([
                'message' => 'Impossible de supprimer cette ville, des départements y sont rattachés.'
            ], 400);
        }

        $em->remove($ville);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }


}
