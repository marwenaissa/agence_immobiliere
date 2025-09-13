<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Relation OneToOne avec OperationBien
    #[ORM\OneToOne(inversedBy: 'facture')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OperationBien $operation = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $dateFacture = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantTotal = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $commissionAgence = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $fichierFacture = null; // Chemin ou nom du fichier (PDF, Word, image, etc.)

    // --- Getters / Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOperation(): ?OperationBien
    {
        return $this->operation;
    }

    public function setOperation(OperationBien $operation): static
    {
        $this->operation = $operation;

        return $this;
    }

    public function getDateFacture(): ?\DateTimeImmutable
    {
        return $this->dateFacture;
    }

    public function setDateFacture(\DateTimeImmutable $dateFacture): static
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    public function getMontantTotal(): ?string
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(string $montantTotal): static
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    public function getCommissionAgence(): ?string
    {
        return $this->commissionAgence;
    }

    public function setCommissionAgence(?string $commissionAgence): static
    {
        $this->commissionAgence = $commissionAgence;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getFichierFacture(): ?string
    {
        return $this->fichierFacture;
    }

    public function setFichierFacture(?string $fichierFacture): static
    {
        $this->fichierFacture = $fichierFacture;

        return $this;
    }
}
