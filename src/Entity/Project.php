<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\GradeTrait;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ApiResource]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[UniqueConstraint(name: "UC_project_code", columns: ["code"])]
class Project
{
    use GradeTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Supervisor $supervisor = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $repositoryUrl = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Functionality::class, orphanRemoval: true)]
    private Collection $functionalities;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: MilestoneMeeting::class, orphanRemoval: true)]
    private Collection $milestoneMeetings;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: GuidanceMeeting::class, orphanRemoval: true)]
    private Collection $guidanceMeetings;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Assignment::class, orphanRemoval: true)]
    private Collection $assignments;

    #[ORM\OneToOne(mappedBy: 'project', cascade: ['persist', 'remove'])]
    private ?CustomSupervisoryPlan $supervisoryPlan = null;

    #[ORM\OneToOne(mappedBy: 'project', cascade: ['persist', 'remove'])]
    private ?Student $student = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectFunctionalitiesHistory::class, orphanRemoval: true)]
    private Collection $history;

    #[ORM\Column(length: 8)]
    private ?string $code = null;

    public function __construct()
    {
        $this->functionalities = new ArrayCollection();
        $this->milestoneMeetings = new ArrayCollection();
        $this->guidanceMeetings = new ArrayCollection();
        $this->assignments = new ArrayCollection();
        $this->history = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupervisor(): ?Supervisor
    {
        return $this->supervisor;
    }

    public function setSupervisor(Supervisor $supervisor): void
    {
        $this->supervisor = $supervisor;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getRepositoryUrl(): ?string
    {
        return $this->repositoryUrl;
    }

    public function setRepositoryUrl(?string $repositoryUrl): void
    {
        $this->repositoryUrl = $repositoryUrl;
    }

    /**
     * @return Collection<int, Functionality>
     */
    public function getFunctionalities(): Collection
    {
        return $this->functionalities;
    }

    public function addFunctionality(Functionality $functionality): void
    {
        if (!$this->functionalities->contains($functionality)) {
            $this->functionalities->add($functionality);
            $functionality->setProject($this);
        }
    }

    public function removeFunctionality(Functionality $functionality): void
    {
        if ($this->functionalities->removeElement($functionality) && $functionality->getProject() === $this) {
            $functionality->setProject(null);
        }
    }

    /**
     * @return Collection<int, MilestoneMeeting>
     */
    public function getMilestoneMeetings(): Collection
    {
        return $this->milestoneMeetings;
    }

    public function addMilestoneMeeting(MilestoneMeeting $milestoneMeeting): void
    {
        if (!$this->milestoneMeetings->contains($milestoneMeeting)) {
            $this->milestoneMeetings->add($milestoneMeeting);
            $milestoneMeeting->setProject($this);
        }
    }

    public function removeMilestoneMeeting(MilestoneMeeting $milestoneMeeting): void
    {
        if ($this->milestoneMeetings->removeElement($milestoneMeeting) && $milestoneMeeting->getProject() === $this) {
            $milestoneMeeting->setProject(null);
        }
    }

    /**
     * @return Collection<int, GuidanceMeeting>
     */
    public function getGuidanceMeetings(): Collection
    {
        return $this->guidanceMeetings;
    }

    public function addGuidanceMeeting(GuidanceMeeting $guidanceMeeting): void
    {
        if (!$this->guidanceMeetings->contains($guidanceMeeting)) {
            $this->guidanceMeetings->add($guidanceMeeting);
            $guidanceMeeting->setProject($this);
        }
    }

    public function removeGuidanceMeeting(GuidanceMeeting $guidanceMeeting): void
    {
        if ($this->guidanceMeetings->removeElement($guidanceMeeting) && $guidanceMeeting->getProject() === $this) {
            $guidanceMeeting->setProject(null);
        }
    }

    /**
     * @return Collection<int, Assignment>
     */
    public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment(Assignment $assignment): self
    {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments->add($assignment);
            $assignment->setProject($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): self
    {
        if ($this->assignments->removeElement($assignment) && $assignment->getProject() === $this) {
            $assignment->setProject(null);
        }

        return $this;
    }

    public function getSupervisoryPlan(): ?CustomSupervisoryPlan
    {
        return $this->supervisoryPlan;
    }

    public function setSupervisoryPlan(CustomSupervisoryPlan $supervisoryPlan): self
    {
        // set the owning side of the relation if necessary
        if ($supervisoryPlan->getProject() !== $this) {
            $supervisoryPlan->setProject($this);
        }

        $this->supervisoryPlan = $supervisoryPlan;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): void
    {
        // set the owning side of the relation if necessary
        if ($student->getProject() !== $this) {
            $student->setProject($this);
        }

        $this->student = $student;
    }

    /**
     * @return Collection<int, ProjectFunctionalitiesHistory>
     */
    public function getHistories(): Collection
    {
        return $this->history;
    }

    public function addHistory(ProjectFunctionalitiesHistory $numberOfOpenItem): self
    {
        if (!$this->history->contains($numberOfOpenItem)) {
            $this->history->add($numberOfOpenItem);
            $numberOfOpenItem->setProject($this);
        }

        return $this;
    }

    public function removeHistory(ProjectFunctionalitiesHistory $numberOfOpenItem): self
    {
        if ($this->history->removeElement($numberOfOpenItem)) {
            // set the owning side to null (unless already changed)
            if ($numberOfOpenItem->getProject() === $this) {
                $numberOfOpenItem->setProject(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
