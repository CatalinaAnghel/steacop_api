<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SupervisoryPlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: SupervisoryPlanRepository::class)]
class SupervisoryPlan {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $hasLowStructure = null;

    #[ORM\Column]
    private ?bool $hasLowSupport = null;

    #[ORM\OneToMany(mappedBy: 'supervisoryPlan', targetEntity: Student::class)]
    private Collection $students;

    public function __construct() {
        $this->students = new ArrayCollection();
    }

    public function setId(int $id): void {
        $this->id = $id;
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

    public function isHasLowStructure(): ?bool {
        return $this->hasLowStructure;
    }

    public function setHasLowStructure(bool $hasLowStructure): self {
        $this->hasLowStructure = $hasLowStructure;

        return $this;
    }

    public function isHasLowSupport(): ?bool {
        return $this->hasLowSupport;
    }

    public function setHasLowSupport(bool $hasLowSupport): self {
        $this->hasLowSupport = $hasLowSupport;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection {
        return $this->students;
    }

    public function addStudent(Student $student): self {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setSupervisoryPlan($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getSupervisoryPlan() === $this) {
                $student->setSupervisoryPlan(null);
            }
        }

        return $this;
    }
}
