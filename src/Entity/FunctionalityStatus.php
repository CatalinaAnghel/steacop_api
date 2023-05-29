<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\SortableTrait;
use App\Repository\FunctionalityStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: FunctionalityStatusRepository::class)]
class FunctionalityStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'functionalityStatus', targetEntity: Functionality::class, orphanRemoval: true)]
    private Collection $functionalities;

    #[ORM\OneToMany(mappedBy: 'oldStatus', targetEntity: FunctionalityStatusHistory::class, orphanRemoval: true)]
    private Collection $oldHistories;

    #[ORM\OneToMany(mappedBy: 'newStatus', targetEntity: FunctionalityStatusHistory::class, orphanRemoval: true)]
    private Collection $newHistories;

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: ProjectFunctionalitiesHistory::class, orphanRemoval: true)]
    private Collection $projectFunctionalitiesHistories;

    use SortableTrait;

    public function __construct()
    {
        $this->functionalities = new ArrayCollection();
        $this->oldHistories = new ArrayCollection();
        $this->newHistories = new ArrayCollection();
        $this->projectFunctionalitiesHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Functionality>
     */
    public function getFunctionalities(): Collection
    {
        return $this->functionalities;
    }

    public function addFunctionality(Functionality $functionality): self
    {
        if (!$this->functionalities->contains($functionality)) {
            $this->functionalities->add($functionality);
            $functionality->setFunctionalityStatus($this);
        }

        return $this;
    }

    public function removeFunctionality(Functionality $functionality): self
    {
        if ($this->functionalities->removeElement($functionality) &&
            $functionality->getFunctionalityStatus() === $this) {
            $functionality->setFunctionalityStatus(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, FunctionalityStatusHistory>
     */
    public function getOldHistories(): Collection
    {
        return $this->oldHistories;
    }

    public function addOldHistory(FunctionalityStatusHistory $history): self
    {
        if (!$this->oldHistories->contains($history)) {
            $this->oldHistories->add($history);
            $history->setOldStatus($this);
        }

        return $this;
    }

    public function removeOldHistory(FunctionalityStatusHistory $history): self
    {
        if ($this->oldHistories->removeElement($history) && $history->getOldStatus() === $this) {
            $history->setOldStatus(null);

        }

        return $this;
    }

    /**
     * @return Collection<int, FunctionalityStatusHistory>
     */
    public function getNewHistories(): Collection
    {
        return $this->newHistories;
    }

    public function addNewHistories(FunctionalityStatusHistory $functionalityStatusHistory): self
    {
        if (!$this->newHistories->contains($functionalityStatusHistory)) {
            $this->newHistories->add($functionalityStatusHistory);
            $functionalityStatusHistory->setNewStatus($this);
        }

        return $this;
    }

    public function removeNewHistories(FunctionalityStatusHistory $functionalityStatusHistory): self
    {
        if ($this->newHistories->removeElement($functionalityStatusHistory) &&
            $functionalityStatusHistory->getNewStatus() === $this) {
            $functionalityStatusHistory->setNewStatus(null);

        }

        return $this;
    }

    /**
     * @return Collection<int, ProjectFunctionalitiesHistory>
     */
    public function getProjectFunctionalitiesHistories(): Collection
    {
        return $this->projectFunctionalitiesHistories;
    }

    public function addProjectFunctionalitiesHistory(ProjectFunctionalitiesHistory $projectFunctionalitiesHistory): self
    {
        if (!$this->projectFunctionalitiesHistories->contains($projectFunctionalitiesHistory)) {
            $this->projectFunctionalitiesHistories->add($projectFunctionalitiesHistory);
            $projectFunctionalitiesHistory->setStatus($this);
        }

        return $this;
    }

    public function removeProjectFunctionalitiesHistory(ProjectFunctionalitiesHistory $projectFunctionalitiesHistory): self
    {
        if ($this->projectFunctionalitiesHistories->removeElement($projectFunctionalitiesHistory) &&
            $projectFunctionalitiesHistory->getStatus() === $this
        ) {
            $projectFunctionalitiesHistory->setStatus(null);
        }

        return $this;
    }
}
