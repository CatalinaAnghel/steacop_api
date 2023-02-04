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

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Meeting::class, orphanRemoval: true)]
    private Collection $meetings;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Assignment::class, orphanRemoval: true)]
    private Collection $assignments;

    public function __construct() {
        $this->student = new ArrayCollection();
        $this->functionalities = new ArrayCollection();
        $this->meetings = new ArrayCollection();
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

    public function removeStudent(Student $student): self {
        if ($this->student->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getProject() === $this) {
                $student->setProject(null);
            }
        }

        return $this;
    }

    public function getSupervisor(): ?Supervisor {
        return $this->supervisor;
    }

    public function setSupervisor(Supervisor $supervisor): self {
        $this->supervisor = $supervisor;

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

    public function getRepositoryUrl(): ?string {
        return $this->repositoryUrl;
    }

    public function setRepositoryUrl(?string $repositoryUrl): self {
        $this->repositoryUrl = $repositoryUrl;

        return $this;
    }

    /**
     * @return Collection<int, Functionality>
     */
    public function getFunctionalities(): Collection {
        return $this->functionalities;
    }

    public function addFunctionality(Functionality $functionality): self {
        if (!$this->functionalities->contains($functionality)) {
            $this->functionalities->add($functionality);
            $functionality->setProject($this);
        }

        return $this;
    }

    public function removeFunctionality(Functionality $functionality): self {
        if ($this->functionalities->removeElement($functionality)) {
            // set the owning side to null (unless already changed)
            if ($functionality->getProject() === $this) {
                $functionality->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Meeting>
     */
    public function getMeetings(): Collection {
        return $this->meetings;
    }

    public function addMeeting(Meeting $meeting): self {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings->add($meeting);
            $meeting->setProject($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): self {
        if ($this->meetings->removeElement($meeting)) {
            // set the owning side to null (unless already changed)
            if ($meeting->getProject() === $this) {
                $meeting->setProject(null);
            }
        }

        return $this;
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
        if ($this->assignments->removeElement($assignment)) {
            // set the owning side to null (unless already changed)
            if ($assignment->getProject() === $this) {
                $assignment->setProject(null);
            }
        }

        return $this;
    }
}
