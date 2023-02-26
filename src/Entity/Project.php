<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Student::class, orphanRemoval: true)]
    private Collection $student;

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

    public function __construct() {
        $this->student = new ArrayCollection();
        $this->functionalities = new ArrayCollection();
        $this->milestoneMeetings = new ArrayCollection();
        $this->guidanceMeetings = new ArrayCollection();
        $this->assignments = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudent(): Collection {
        return $this->student;
    }

    public function addStudent(Student $student): self {
        if (!$this->student->contains($student)) {
            $this->student->add($student);
            $student->setProject($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): void {
        if ($this->student->removeElement($student) && $student->getProject() === $this) {
            $student->setProject(null);
        }
    }

    public function getSupervisor(): ?Supervisor {
        return $this->supervisor;
    }

    public function setSupervisor(Supervisor $supervisor): void {
        $this->supervisor = $supervisor;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function getRepositoryUrl(): ?string {
        return $this->repositoryUrl;
    }

    public function setRepositoryUrl(?string $repositoryUrl): void {
        $this->repositoryUrl = $repositoryUrl;
    }

    /**
     * @return Collection<int, Functionality>
     */
    public function getFunctionalities(): Collection {
        return $this->functionalities;
    }

    public function addFunctionality(Functionality $functionality): void {
        if (!$this->functionalities->contains($functionality)) {
            $this->functionalities->add($functionality);
            $functionality->setProject($this);
        }
    }

    public function removeFunctionality(Functionality $functionality): void {
        if ($this->functionalities->removeElement($functionality) && $functionality->getProject() === $this) {
            $functionality->setProject(null);
        }
    }

    /**
     * @return Collection<int, MilestoneMeeting>
     */
    public function getMilestoneMeetings(): Collection {
        return $this->milestoneMeetings;
    }

    public function addMilestoneMeeting(MilestoneMeeting $milestoneMeeting): void {
        if (!$this->milestoneMeetings->contains($milestoneMeeting)) {
            $this->milestoneMeetings->add($milestoneMeeting);
            $milestoneMeeting->setProject($this);
        }
    }

    public function removeMilestoneMeeting(MilestoneMeeting $milestoneMeeting): void {
        if ($this->milestoneMeetings->removeElement($milestoneMeeting) && $milestoneMeeting->getProject() === $this) {
            $milestoneMeeting->setProject(null);
        }
    }

    /**
     * @return Collection<int, GuidanceMeeting>
     */
    public function getGuidanceMeetings(): Collection {
        return $this->guidanceMeetings;
    }

    public function addGuidanceMeeting(GuidanceMeeting $guidanceMeeting): void {
        if (!$this->guidanceMeetings->contains($guidanceMeeting)) {
            $this->guidanceMeetings->add($guidanceMeeting);
            $guidanceMeeting->setProject($this);
        }
    }

    public function removeGuidanceMeeting(GuidanceMeeting $guidanceMeeting): void {
        if ($this->guidanceMeetings->removeElement($guidanceMeeting) && $guidanceMeeting->getProject() === $this) {
            $guidanceMeeting->setProject(null);
        }
    }

    /**
     * @return Collection<int, Assignment>
     */
    public function getAssignments(): Collection {
        return $this->assignments;
    }

    public function addAssignment(Assignment $assignment): self {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments->add($assignment);
            $assignment->setProject($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): self {
        if ($this->assignments->removeElement($assignment) && $assignment->getProject() === $this) {
            $assignment->setProject(null);
        }

        return $this;
    }
}
