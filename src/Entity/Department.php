<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Specialization::class, orphanRemoval: true)]
    private Collection $specializations;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Supervisor::class, orphanRemoval: true)]
    private Collection $supervisors;

    #[Pure] public function __construct() {
        $this->specializations = new ArrayCollection();
        $this->supervisors = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Specialization>
     */
    public function getSpecializations(): Collection {
        return $this->specializations;
    }

    public function addSpecialization(Specialization $specialization): self {
        if (!$this->specializations->contains($specialization)) {
            $this->specializations->add($specialization);
            $specialization->setDepartment($this);
        }

        return $this;
    }

    public function removeSpecialization(Specialization $specialization): self {
        if ($this->specializations->removeElement($specialization)) {
            // set the owning side to null (unless already changed)
            if ($specialization->getDepartment() === $this) {
                $specialization->setDepartment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Supervisor>
     */
    public function getSupervisors(): Collection {
        return $this->supervisors;
    }

    public function addSupervisor(Supervisor $supervisor): self {
        if (!$this->supervisors->contains($supervisor)) {
            $this->supervisors->add($supervisor);
            $supervisor->setDepartment($this);
        }

        return $this;
    }

    public function removeSupervisor(Supervisor $supervisor): self {
        if ($this->supervisors->removeElement($supervisor)) {
            // set the owning side to null (unless already changed)
            if ($supervisor->getDepartment() === $this) {
                $supervisor->setDepartment(null);
            }
        }

        return $this;
    }
}
