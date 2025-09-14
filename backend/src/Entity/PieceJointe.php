<?php

namespace App\Entity;

use App\Repository\PieceJointeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PieceJointeRepository::class)]
class PieceJointe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Une pièce peut être rattachée à un bien OU à un état des lieux, mais pas forcément les deux
    #[ORM\ManyToOne(inversedBy: 'pieceJointes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?BienImmobilier $bien = null;

    #[ORM\ManyToOne(inversedBy: 'pieceJointes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EtatLieu $etatLieu = null;

    #[ORM\Column(length: 255)]
    private ?string $urlFichier = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null; // ex: 'image', 'document', 'contrat', etc.

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBien(): ?BienImmobilier
    {
        return $this->bien;
    }

    public function setBien(?BienImmobilier $bien): static
    {
        $this->bien = $bien;
        return $this;
    }

    public function getEtatLieu(): ?EtatLieu
    {
        return $this->etatLieu;
    }

    public function setEtatLieu(?EtatLieu $etatLieu): static
    {
        $this->etatLieu = $etatLieu;
        return $this;
    }

    public function getUrlFichier(): ?string
    {
        return $this->urlFichier;
    }

    public function setUrlFichier(string $urlFichier): static
    {
        $this->urlFichier = $urlFichier;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }
}
