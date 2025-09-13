<?php

namespace App\Entity;

use App\Repository\OperationBienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperationBienRepository::class)]
class OperationBien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Vente ou Location
    #[ORM\Column(length: 20)]
    private ?string $type = null;

    // Relation avec Bien
    #[ORM\ManyToOne(inversedBy: 'operations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BienImmobilier $bien = null;

    // --- Vente ---
    #[ORM\ManyToOne(targetEntity: Client::class)]
    private ?Client $acheteur = null;

    #[ORM\ManyToOne(targetEntity: Proprietaire::class)]
    private ?Proprietaire $vendeur = null;

    // --- Location ---
    #[ORM\ManyToOne(targetEntity: Client::class)]
    private ?Client $locataire = null;

    #[ORM\ManyToOne(targetEntity: Proprietaire::class)]
    private ?Proprietaire $bailleur = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $dateOperation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $caution = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null; // En cours, Validée, Annulée

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    /**
     * @var Collection<int, EtatLieu>
     */
    #[ORM\OneToMany(mappedBy: 'operation', targetEntity: EtatLieu::class)]
    private Collection $etatLieus;

    public function __construct()
    {
        $this->etatLieus = new ArrayCollection();
    }

    // --- Getters / Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getAcheteur(): ?Client
    {
        return $this->acheteur;
    }

    public function setAcheteur(?Client $acheteur): static
    {
        $this->acheteur = $acheteur;

        return $this;
    }

    public function getVendeur(): ?Proprietaire
    {
        return $this->vendeur;
    }

    public function setVendeur(?Proprietaire $vendeur): static
    {
        $this->vendeur = $vendeur;

        return $this;
    }

    public function getLocataire(): ?Client
    {
        return $this->locataire;
    }

    public function setLocataire(?Client $locataire): static
    {
        $this->locataire = $locataire;

        return $this;
    }

    public function getBailleur(): ?Proprietaire
    {
        return $this->bailleur;
    }

    public function setBailleur(?Proprietaire $bailleur): static
    {
        $this->bailleur = $bailleur;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDateOperation(): ?\DateTimeImmutable
    {
        return $this->dateOperation;
    }

    public function setDateOperation(\DateTimeImmutable $dateOperation): static
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCaution(): ?string
    {
        return $this->caution;
    }

    public function setCaution(?string $caution): static
    {
        $this->caution = $caution;

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

    /**
     * @return Collection<int, EtatLieu>
     */
    public function getEtatLieus(): Collection
    {
        return $this->etatLieus;
    }

    public function addEtatLieu(EtatLieu $etatLieu): static
    {
        if (!$this->etatLieus->contains($etatLieu)) {
            $this->etatLieus->add($etatLieu);
            $etatLieu->setOperation($this);
        }

        return $this;
    }

    public function removeEtatLieu(EtatLieu $etatLieu): static
    {
        if ($this->etatLieus->removeElement($etatLieu)) {
            // set the owning side to null (unless already changed)
            if ($etatLieu->getOperation() === $this) {
                $etatLieu->setOperation(null);
            }
        }

        return $this;
    }
}
