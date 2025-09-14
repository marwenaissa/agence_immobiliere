<?php

namespace App\Controller;

use App\Repository\ProprietaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/proprietaires', name: 'api_proprietaires_')]
class ProprietaireController extends AbstractController
{
    private ProprietaireRepository $proprietaireRepository;

    public function __construct(ProprietaireRepository $proprietaireRepository)
    {
        $this->proprietaireRepository = $proprietaireRepository;
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $proprietaires = $this->proprietaireRepository->findAll();

        $data = [];

        foreach ($proprietaires as $proprietaire) {
            $data[] = [
                'id' => $proprietaire->getId(),
                'nomUtilisateur' => $proprietaire->getUtilisateur()->getNom(),
                'prenomUtilisateur' => $proprietaire->getUtilisateur()->getPrenom(),
                'profession' => $proprietaire->getProfession(),
                'nomBanque' => $proprietaire->getNomBanque(),
                'adresseBanque' => $proprietaire->getAdresseBanque(),
                'rib' => $proprietaire->getRib(),
                'iban' => $proprietaire->getIban(),
            ];
        }

        return $this->json($data);
    }
}

