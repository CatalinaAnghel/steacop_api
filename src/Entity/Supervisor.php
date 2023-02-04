<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\PersonTrait;
use App\Repository\SupervisorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: SupervisorRepository::class)]
class Supervisor {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use PersonTrait;

    #[ORM\ManyToOne(inversedBy: 'supervisors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Department $department = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'supervisor', targetEntity: Project::class, orphanRemoval: true)]
    private Collection $projects;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct() {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getDepartment(): ?Department {
        return $this->department;
    }

    public function setDepartment(?Department $department): self {
        $this->department = $department;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getSupervisoryPlan(): ?SupervisoryPlan {
        return $this->supervisoryPlan;
    }

    public function setSupervisoryPlan(?SupervisoryPlan $supervisoryPlan): self {
        $this->supervisoryPlan = $supervisoryPlan;

        return $this;
    }

    public function getProjects(): Collection {
        return $this->projects;
    }

    public function addProject(Project $project): void
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setSupervisor($this);
        }
    }

    public function removeRating(Project $project): void
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getSupervisor() === $this) {
                $project->setSupervisor(null);
            }
        }
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(User $user): self {
        $this->user = $user;

        return $this;
    }
}
