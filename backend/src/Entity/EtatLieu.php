<?php

namespace App\Entity;

use App\Repository\EtatLieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtatLieuRepository::class)]
class EtatLieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'etatLieus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OperationBien $operation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEtat = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    /**
     * @var Collection<int, PieceJointe>
     */
    #[ORM\OneToMany(mappedBy: 'etatLieu', targetEntity: PieceJointe::class)]
    private Collection $pieceJointes;

    public function __construct()
    {
        $this->pieceJointes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOperation(): ?OperationBien
    {
        return $this->operation;
    }

    public function setOperation(?OperationBien $operation): static
    {
        $this->operation = $operation;

        return $this;
    }

    public function getDateEtat(): ?\DateTimeInterface
    {
        return $this->dateEtat;
    }

    public function setDateEtat(\DateTimeInterface $dateEtat): static
    {
        $this->dateEtat = $dateEtat;

        return $this;
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
     * @return Collection<int, PieceJointe>
     */
    public function getPieceJointes(): Collection
    {
        return $this->pieceJointes;
    }

    public function addPieceJointe(PieceJointe $pieceJointe): static
    {
        if (!$this->pieceJointes->contains($pieceJointe)) {
            $this->pieceJointes->add($pieceJointe);
            $pieceJointe->setEtatLieu($this);
        }

        return $this;
    }

    public function removePieceJointe(PieceJointe $pieceJointe): static
    {
        if ($this->pieceJointes->removeElement($pieceJointe)) {
            // set the owning side to null (unless already changed)
            if ($pieceJointe->getEtatLieu() === $this) {
                $pieceJointe->setEtatLieu(null);
            }
        }

        return $this;
    }
}
