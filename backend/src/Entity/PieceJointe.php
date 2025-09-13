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

    #[ORM\ManyToOne(inversedBy: 'pieceJointes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EtatLieu $etatLieu = null;

    #[ORM\ManyToOne(inversedBy: 'pieceJointes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BienImmobilier $bien = null;

    #[ORM\Column(length: 255)]
    private ?string $urlFichier = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBien(): ?BienImmobilier
    {
        return $this->bien;
    }

    public function setBien(?BienImmobilier $bien): static
    {
        $this->bien = $bien;
        return $this;
    }

}
