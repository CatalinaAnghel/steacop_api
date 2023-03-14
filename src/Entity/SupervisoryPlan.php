<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Contracts\AbstractSupervisoryPlan;
use App\Repository\SupervisoryPlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: SupervisoryPlanRepository::class)]
class SupervisoryPlan extends AbstractSupervisoryPlan {
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

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function isHasLowStructure(): ?bool {
        return $this->hasLowStructure;
    }

    public function setHasLowStructure(bool $hasLowStructure): void {
        $this->hasLowStructure = $hasLowStructure;
    }

    public function isHasLowSupport(): ?bool {
        return $this->hasLowSupport;
    }

    public function setHasLowSupport(bool $hasLowSupport): void {
        $this->hasLowSupport = $hasLowSupport;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection {
        return $this->students;
    }

    public function addStudent(Student $student): void {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setSupervisoryPlan($this);
        }
    }

    public function removeStudent(Student $student): void {
        if ($this->students->removeElement($student) && $student->getSupervisoryPlan() === $this) {
            $student->setSupervisoryPlan(null);
        }
    }
}
