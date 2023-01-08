<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\AssignmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ApiResource]
#[ORM\Entity(repositoryClass: AssignmentRepository::class)]
class Assignment {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'assignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\OneToMany(mappedBy: 'assignment', targetEntity: Document::class, orphanRemoval: true)]
    private Collection $documents;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $turnedInDate = null;

    #[ORM\Column(length: 128)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $grade = null;

    use TimestampableTrait;

    #[Pure] public function __construct() {
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getProject(): ?Project {
        return $this->project;
    }

    public function setProject(?Project $project): self {
        $this->project = $project;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): self {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection {
        return $this->documents;
    }

    public function addDocument(Document $document): self {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setAssignment($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getAssignment() === $this) {
                $document->setAssignment(null);
            }
        }

        return $this;
    }

    public function getTurnedInDate(): ?\DateTimeInterface {
        return $this->turnedInDate;
    }

    public function setTurnedInDate(?\DateTimeInterface $turnedInDate): self {
        $this->turnedInDate = $turnedInDate;

        return $this;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getGrade(): ?float {
        return $this->grade;
    }

    public function setGrade(?float $grade): self {
        $this->grade = $grade;

        return $this;
    }
}
