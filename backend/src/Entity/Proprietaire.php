<?php

namespace App\Entity;

use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprietaireRepository::class)]
class Proprietaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    #[ORM\OneToOne(inversedBy: 'proprietaire', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomBanque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseBanque = null;

    #[ORM\Column(length: 34, nullable: true)]
    private ?string $rib = null;

    #[ORM\Column(length: 34, nullable: true)]
    private ?string $iban = null;

    /**
     * @var Collection<int, BienImmobilier>
     */
    #[ORM\OneToMany(mappedBy: 'proprietaire', targetEntity: BienImmobilier::class)]
    private Collection $bienImmobiliers;

    public function __construct()
    {
        $this->bienImmobiliers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getNomBanque(): ?string
    {
        return $this->nomBanque;
    }

    public function setNomBanque(?string $nomBanque): static
    {
        $this->nomBanque = $nomBanque;
        return $this;
    }

    public function getAdresseBanque(): ?string
    {
        return $this->adresseBanque;
    }

    public function setAdresseBanque(?string $adresseBanque): static
    {
        $this->adresseBanque = $adresseBanque;
        return $this;
    }

     public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(?string $rib): static
    {
        $this->rib = $rib;
        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): static
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * @return Collection<int, BienImmobilier>
     */
    public function getBienImmobiliers(): Collection
    {
        return $this->bienImmobiliers;
    }

    public function addBienImmobilier(BienImmobilier $bienImmobilier): static
    {
        if (!$this->bienImmobiliers->contains($bienImmobilier)) {
            $this->bienImmobiliers->add($bienImmobilier);
            $bienImmobilier->setProprietaire($this);
        }

        return $this;
    }

    public function removeBienImmobilier(BienImmobilier $bienImmobilier): static
    {
        if ($this->bienImmobiliers->removeElement($bienImmobilier)) {
            // set the owning side to null (unless already changed)
            if ($bienImmobilier->getProprietaire() === $this) {
                $bienImmobilier->setProprietaire(null);
            }
        }

        return $this;
    }
}
