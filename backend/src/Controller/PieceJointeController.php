<?php

namespace App\Controller;

use App\Entity\PieceJointe;
use App\Entity\BienImmobilier;
use App\Repository\BienImmobilierRepository;
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
use Symfony\Component\String\Slugger\SluggerInterface;


final class PieceJointeController extends AbstractController{
    // 🔹 Upload multiple pièces jointes
    #[Route('/{id}/pieces', name: 'bien_upload_pieces', methods: ['POST'])]
    public function uploadPieces(int $id, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): JsonResponse
    {
        $bien = $em->getRepository(BienImmobilier::class)->find($id);
        if (!$bien) return $this->json(['error' => 'Bien non trouvé'], 404);

        $files = $request->files->get('files');
        $descriptions = $request->request->all()['descriptions'] ?? [];

        if (!$files || !count($files)) return $this->json(['error' => 'Aucun fichier reçu'], 400);

        foreach ($files as $index => $file) {
            $piece = new PieceJointe();
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('pieces_directory'), $newFilename);
            } catch (\Exception $e) {
                return $this->json(['error' => 'Erreur upload: '.$e->getMessage()], 500);
            }

            $piece->setUrlFichier($newFilename)
                  ->setBien($bien)
                  ->setDescription($descriptions[$index] ?? null)
                  ->setType('image');
            $em->persist($piece);
        }

        $em->flush();
        return $this->json(['message' => 'Fichiers uploadés avec succès']);
    }

    #[Route('/{id}/pieces', name: 'bien_get_pieces', methods: ['GET'])]
    public function getPieces(int $id, EntityManagerInterface $em): JsonResponse
    {
        $bien = $em->getRepository(BienImmobilier::class)->find($id);
        if (!$bien) return $this->json(['error' => 'Bien non trouvé'], 404);

        $pieces = $bien->getPieces()->map(fn($p) => [
            'id' => $p->getId(),
            'urlFichier' => '/uploads/pieces/'.$p->getUrlFichier(),
            'description' => $p->getDescription(),
        ]);

        return $this->json($pieces);
    }
    

}


