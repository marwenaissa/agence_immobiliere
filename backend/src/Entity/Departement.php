<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'departements')]
    private ?Ville $ville = null;

    /**
     * @var Collection<int, BienImmobilier>
     */
    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: BienImmobilier::class)]
    private Collection $bienImmobiliers;

    public function __construct()
    {
        $this->bienImmobiliers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

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
            $bienImmobilier->setDepartement($this);
        }

        return $this;
    }

    public function removeBienImmobilier(BienImmobilier $bienImmobilier): static
    {
        if ($this->bienImmobiliers->removeElement($bienImmobilier)) {
            // set the owning side to null (unless already changed)
            if ($bienImmobilier->getDepartement() === $this) {
                $bienImmobilier->setDepartement(null);
            }
        }

        return $this;
    }
}
