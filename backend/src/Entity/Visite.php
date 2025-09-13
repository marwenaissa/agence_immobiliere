<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisiteRepository::class)]
class Visite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'visites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BienImmobilier $bien = null;

    #[ORM\ManyToOne(inversedBy: 'visites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Visiteur $relation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateProgrammee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateReelle = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

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

    public function getRelation(): ?Visiteur
    {
        return $this->relation;
    }

    public function setRelation(?Visiteur $relation): static
    {
        $this->relation = $relation;

        return $this;
    }

    public function getDateProgrammee(): ?\DateTimeInterface
    {
        return $this->dateProgrammee;
    }

    public function setDateProgrammee(\DateTimeInterface $dateProgrammee): static
    {
        $this->dateProgrammee = $dateProgrammee;

        return $this;
    }

    public function getDateReelle(): ?\DateTimeInterface
    {
        return $this->dateReelle;
    }

    public function setDateReelle(\DateTimeInterface $dateReelle): static
    {
        $this->dateReelle = $dateReelle;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
