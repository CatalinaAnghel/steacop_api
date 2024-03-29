<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\FunctionalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ApiResource]
#[ORM\Entity(repositoryClass: FunctionalityRepository::class)]
class Functionality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'functionalities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'functionalities')]
    private ?self $parentFunctionality = null;

    #[ORM\OneToMany(mappedBy: 'parentFunctionality', targetEntity: self::class, orphanRemoval: true)]
    private Collection $functionalities;

    #[ORM\ManyToOne(inversedBy: 'functionalities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FunctionalityType $type = null;

    #[ORM\ManyToOne(inversedBy: 'functionalities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FunctionalityStatus $functionalityStatus = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column]
    private int $code;

    #[ORM\OneToMany(mappedBy: 'functionality', targetEntity: FunctionalityAttachment::class, orphanRemoval: true)]
    private Collection $functionalityAttachments;

    #[ORM\OneToMany(mappedBy: 'functionality', targetEntity: FunctionalityStatusHistory::class, orphanRemoval: true)]
    private Collection $history;

    #[ORM\Column(nullable: true)]
    private ?int $orderNumber = null;

    use TimestampableTrait;

    #[Pure] public function __construct()
    {
        $this->functionalities = new ArrayCollection();
        $this->functionalityAttachments = new ArrayCollection();
        $this->history = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getParentFunctionality(): ?self
    {
        return $this->parentFunctionality;
    }

    public function setParentFunctionality(?self $parentFunctionality): self
    {
        $this->parentFunctionality = $parentFunctionality;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFunctionalities(): Collection
    {
        return $this->functionalities;
    }

    public function addFunctionality(self $functionality): self
    {
        if (!$this->functionalities->contains($functionality)) {
            $this->functionalities->add($functionality);
            $functionality->setParentFunctionality($this);
        }

        return $this;
    }

    public function removeFunctionality(self $functionality): self
    {
        if ($this->functionalities->removeElement($functionality) &&
            $functionality->getParentFunctionality() === $this) {
            $functionality->setParentFunctionality(null);
        }

        return $this;
    }

    public function getType(): ?FunctionalityType
    {
        return $this->type;
    }

    public function setType(?FunctionalityType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFunctionalityStatus(): ?FunctionalityStatus
    {
        return $this->functionalityStatus;
    }

    public function setFunctionalityStatus(?FunctionalityStatus $functionalityStatus): self
    {
        $this->functionalityStatus = $functionalityStatus;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, FunctionalityAttachment>
     */
    public function getFunctionalityAttachments(): Collection
    {
        return $this->functionalityAttachments;
    }

    public function addFunctionalityAttachment(FunctionalityAttachment $functionalityAttachment): self
    {
        if (!$this->functionalityAttachments->contains($functionalityAttachment)) {
            $this->functionalityAttachments->add($functionalityAttachment);
            $functionalityAttachment->setFunctionality($this);
        }

        return $this;
    }

    public function removeFunctionalityAttachment(FunctionalityAttachment $functionalityAttachment): self
    {
        if ($this->functionalityAttachments->removeElement($functionalityAttachment) &&
            $functionalityAttachment->getFunctionality() === $this) {
            $functionalityAttachment->setFunctionality(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, FunctionalityStatusHistory>
     */
    public function getHistory(): Collection
    {
        return $this->history;
    }

    public function addHistory(FunctionalityStatusHistory $history): self
    {
        if (!$this->history->contains($history)) {
            $this->history->add($history);
            $history->setFunctionality($this);
        }

        return $this;
    }

    public function removeHistory(FunctionalityStatusHistory $history): self
    {
        if ($this->history->removeElement($history) && $history->getFunctionality() === $this) {
            $history->setFunctionality(null);
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    /**
     * @param int|null $orderNumber
     * @return $this
     */
    public function setOrderNumber(?int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }
}
