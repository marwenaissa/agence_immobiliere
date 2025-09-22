<?php
namespace App\Controller;

use App\Entity\BienImmobilier;
use App\Entity\Utilisateur;
use App\Entity\Visite;
use App\Entity\Client;
use App\Entity\Visiteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;



#[Route('/api/visiteurs')]
class VisiteurController extends AbstractController{
    
    #[Route('/{id}/convert-to-client', name: 'visiteur_to_client', methods: ['POST'])]
    public function convertVisiteurToClient(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $visiteur = $em->getRepository(Visiteur::class)->find($id);

        if (!$visiteur) {
            return $this->json(['error' => 'Visiteur non trouvé'], 404);
        }

        $utilisateur = $visiteur->getUtilisateur();

        if ($utilisateur->getClient()) {
            return $this->json(['error' => 'Déjà client'], 400);
        }

        
        $data = json_decode($request->getContent(), true);
        $passeport = $data['passeport'] ?? null;



        $client = new Client();
        $client->setUtilisateur($utilisateur);
        $client->setProfession($visiteur->getProfession());
        $client->setPasseport($passeport);

        $utilisateur->setClient($client);

        $em->persist($client);
        $em->flush();

        return $this->json([
            'id' => $client->getId(),
            'nom' => $utilisateur->getNom(),
            'prenom' => $utilisateur->getPrenom(),
            'email' => $utilisateur->getEmail(),
            'cin' => $utilisateur->getCin(),
            'telephone' => $utilisateur->getTelephone(),
            'profession' => $client->getProfession(),
            'passeport' => $client->getPasseport(),
        ], 201);
    }


    #[Route('/create', name: 'visiteur_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérification des champs requis
        if (empty($data['nom']) || empty($data['prenom'])) {
            return new JsonResponse(['error' => 'Nom et prénom sont requis.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Création de l'utilisateur
        $utilisateur = new Utilisateur();
        $utilisateur->setNom($data['nom']);
        $utilisateur->setPrenom($data['prenom']);
        $utilisateur->setEmail($data['email'] ?? null);
        $utilisateur->setCin($data['cin'] ?? null);
        $utilisateur->setTelephone($data['telephone'] ?? null);
        $utilisateur->setAdresse($data['adresse'] ?? null); // Optionnel
        $utilisateur->setDateNaissance(
            !empty($data['dateNaissance']) ? new \DateTime($data['dateNaissance']) : null
        );

        // Création du visiteur
        $visiteur = new Visiteur();
        $visiteur->setUtilisateur($utilisateur); // ✅ liaison obligatoire
        $visiteur->setProfession($data['profession'] ?? null);
        $visiteur->setPreference($data['preference'] ?? null);
        $visiteur->setPasseport($data['passeport'] ?? null);
        $visiteur->setBudgetMax($data['budgetMax'] ?? null);

        // Liaison côté utilisateur aussi (bi-directionnelle)
        $utilisateur->setVisiteur($visiteur);

        // Enregistrement en base
        $em->persist($utilisateur); // cascade: persist Visiteur
        $em->flush();

        return $this->json([
            'message' => 'Visiteur ajouté avec succès',
            'data' => [
                'id' => $visiteur->getId(),
                'nom' => $utilisateur->getNom(),
                'prenom' => $utilisateur->getPrenom(),
                'email' => $utilisateur->getEmail(),
                'cin' => $utilisateur->getCin(),
                'telephone' => $utilisateur->getTelephone(),
                'profession' => $visiteur->getProfession(),
                'passeport' => $visiteur->getPasseport(),
                'budgetMax' => $visiteur->getBudgetMax()
            ]
        ], JsonResponse::HTTP_CREATED);
    }


}
