<?php

namespace App\Controller;

use App\Entity\BienImmobilier;
use App\Entity\PieceJointe;


use App\Repository\BienImmobilierRepository;
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
use App\Entity\Proprietaire;
use App\Entity\TypeBien;
use App\Entity\Departement;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/api/biens')]
class BienImmobilierController extends AbstractController
{
        
    #[Route('', name: 'bien_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): JsonResponse
    {
        $data = $request->request->all();

        if (empty($data)) {
            $data = json_decode($request->getContent(), true);
        }

        if (!$data) {
            return $this->json(['error' => 'Aucun JSON reçu ou JSON invalide.'], 400);
        }

        $requiredFields = ['titre', 'description', 'adresse', 'statut', 'offreType', 'montant', 'type_id', 'proprietaire_id', 'departement_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Le champ '$field' est obligatoire."], 400);
            }
        }

        $bien = new BienImmobilier();
        $bien->setTitre($data['titre']);
        $bien->setDescription($data['description']);
        $bien->setAdresse($data['adresse']);
        $bien->setStatut($data['statut']);
        $bien->setOffreType($data['offreType']);
        $bien->setMantant($data['montant']);

        $type = $em->getRepository(TypeBien::class)->find($data['type_id']);
        $proprietaire = $em->getRepository(Proprietaire::class)->find($data['proprietaire_id']);
        $departement = $em->getRepository(Departement::class)->find($data['departement_id']);

        if (!$type || !$proprietaire || !$departement) {
            return $this->json(['error' => 'TypeBien, Proprietaire ou Departement introuvable.'], 400);
        }

        $bien->setType($type);
        $bien->setProprietaire($proprietaire);
        $bien->setDepartement($departement);

        $em->persist($bien);
        $em->flush();

        // 🔹 Gestion des fichiers multiples
        $files = $request->files->get('files');
        if ($files && count($files) > 0) {
            foreach ($files as $file) {
                $piece = new PieceJointe();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move($this->getParameter('pieces_directory'), $newFilename);
                } catch (\Exception $e) {
                    return $this->json(['error' => 'Erreur upload fichier: '.$e->getMessage()], 500);
                }

                $piece->setUrlFichier($newFilename)
                    ->setBien($bien)
                    ->setType('image');

                $em->persist($piece);
            }
            $em->flush();
        }

        return $this->json($bien, 201, [], ['groups' => 'bien:read']);
    }



    #[Route('', name: 'bien_list', methods: ['GET'])]
    public function list(BienImmobilierRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $biens = $repository->findAll();
        

        $json = $serializer->serialize(
            $biens,
            'json',
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId(),
            ]
        );

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
        $bien->setMantant($data['montant'] ?? $bien->getMantant());

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
