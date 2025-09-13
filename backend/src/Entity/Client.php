<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    #[ORM\OneToOne(inversedBy: 'client', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $passeport = null;

    /**
     * @var Collection<int, OperationBien>
     */
    #[ORM\OneToMany(mappedBy: 'acheteur', targetEntity: OperationBien::class)]
    private Collection $operationBiens;

    public function __construct()
    {
        $this->operationBiens = new ArrayCollection();
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

    public function getPasseport(): ?string
    {
        return $this->passeport;
    }

    public function setPasseport(?string $passeport): static
    {
        $this->passeport = $passeport;
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
            $operationBien->setAcheteur($this);
        }

        return $this;
    }

    public function removeOperationBien(OperationBien $operationBien): static
    {
        if ($this->operationBiens->removeElement($operationBien)) {
            // set the owning side to null (unless already changed)
            if ($operationBien->getAcheteur() === $this) {
                $operationBien->setAcheteur(null);
            }
        }

        return $this;
    }
}
