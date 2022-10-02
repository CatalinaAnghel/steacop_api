<?php

namespace App\Entity;

use App\Entity\Traits\PersonTrait;
use App\Repository\SupervisorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupervisorRepository::class)]
class Supervisor extends User {
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

    #[ORM\OneToOne(mappedBy: 'supervisor', cascade: ['persist', 'remove'])]
    private ?Project $project = null;

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

    public function getProject(): ?Project {
        return $this->project;
    }

    public function setProject(Project $project): self {
        // set the owning side of the relation if necessary
        if ($project->getSupervisor() !== $this) {
            $project->setSupervisor($this);
        }

        $this->project = $project;

        return $this;
    }
}
