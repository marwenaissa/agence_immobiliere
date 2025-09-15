<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;


final class UtilisateurController extends AbstractController{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UtilisateurController.php',
        ]);
    }

    #[Route('/api/visiteurs', name: 'get_visiteurs', methods: ['GET'])]
    public function getVisiteurs(EntityManagerInterface $em): JsonResponse
    {
        $visiteurs = $em->getRepository(Utilisateur::class)->findAll(); 

        $data = array_map(fn($v) => [
            'id' => $v->getId(),
            'prenom' => $v->getPrenom(),
            'nom' => $v->getNom()
        ], $visiteurs);

        return $this->json($data);
    }


}
