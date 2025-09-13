<?php

namespace App\Entity;

use App\Repository\TypeBienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeBienRepository::class)]
class TypeBien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, BienImmobilier>
     */
    #[ORM\OneToMany(mappedBy: 'type', targetEntity: BienImmobilier::class)]
    private Collection $bienImmobiliers;

    public function __construct()
    {
        $this->bienImmobiliers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

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
            $bienImmobilier->setType($this);
        }

        return $this;
    }

    public function removeBienImmobilier(BienImmobilier $bienImmobilier): static
    {
        if ($this->bienImmobiliers->removeElement($bienImmobilier)) {
            // set the owning side to null (unless already changed)
            if ($bienImmobilier->getType() === $this) {
                $bienImmobilier->setType(null);
            }
        }

        return $this;
    }
}
