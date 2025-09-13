<?php

namespace App\Entity;

use App\Repository\BienImmobilierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BienImmobilierRepository::class)]
class BienImmobilier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $surface = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbreChambres = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(length: 50)]
    private ?string $offreType = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $mantant = null;

    #[ORM\ManyToOne(inversedBy: 'bienImmobiliers')]
    private ?TypeBien $type = null;

    #[ORM\ManyToOne(inversedBy: 'bienImmobiliers')]
    private ?Departement $departement = null;

    #[ORM\ManyToOne(inversedBy: 'bienImmobiliers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Proprietaire $proprietaire = null;

    /**
     * @var Collection<int, Visite>
     */
    #[ORM\OneToMany(mappedBy: 'bien', targetEntity: Visite::class)]
    private Collection $visites;

    /**
     * @var Collection<int, OperationBien>
     */
    #[ORM\OneToMany(mappedBy: 'bien', targetEntity: OperationBien::class)]
    private Collection $operationBiens;

    public function __construct()
    {
        $this->visites = new ArrayCollection();
        $this->operationBiens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(?float $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function getNbreChambres(): ?int
    {
        return $this->nbreChambres;
    }

    public function setNbreChambres(?int $nbreChambres): static
    {
        $this->nbreChambres = $nbreChambres;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

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

    public function getOffreType(): ?string
    {
        return $this->offreType;
    }

    public function setOffreType(string $offreType): static
    {
        $this->offreType = $offreType;

        return $this;
    }

    public function getMantant(): ?string
    {
        return $this->mantant;
    }

    public function setMantant(string $mantant): static
    {
        $this->mantant = $mantant;

        return $this;
    }

    public function getType(): ?TypeBien
    {
        return $this->type;
    }

    public function setType(?TypeBien $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function getProprietaire(): ?Proprietaire
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Proprietaire $proprietaire): static
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * @return Collection<int, Visite>
     */
    public function getVisites(): Collection
    {
        return $this->visites;
    }

    public function addVisite(Visite $visite): static
    {
        if (!$this->visites->contains($visite)) {
            $this->visites->add($visite);
            $visite->setBien($this);
        }

        return $this;
    }

    public function removeVisite(Visite $visite): static
    {
        if ($this->visites->removeElement($visite)) {
            // set the owning side to null (unless already changed)
            if ($visite->getBien() === $this) {
                $visite->setBien(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OperationBien>
     */
    public function getOperationBiens(): Collection
    {
        return $this->operationBiens;
    }

    public function addOperationBien(OperationBien $operationBien): static
    {
        if (!$this->operationBiens->contains($operationBien)) {
            $this->operationBiens->add($operationBien);
            $operationBien->setBien($this);
        }

        return $this;
    }

    public function removeOperationBien(OperationBien $operationBien): static
    {
        if ($this->operationBiens->removeElement($operationBien)) {
            // set the owning side to null (unless already changed)
            if ($operationBien->getBien() === $this) {
                $operationBien->setBien(null);
            }
        }

        return $this;
    }
}
