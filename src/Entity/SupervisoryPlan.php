<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SupervisoryPlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(10)]
    private ?int $numberOfAssignments = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(10)]
    private ?int $numberOfGuidanceMeetings = null;

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
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getSupervisoryPlan() === $this) {
                $student->setSupervisoryPlan(null);
            }
        }
    }

    public function getNumberOfAssignments(): ?int {
        return $this->numberOfAssignments;
    }

    public function setNumberOfAssignments(int $numberOfAssignments): void {
        $this->numberOfAssignments = $numberOfAssignments;
    }

    public function getNumberOfGuidanceMeetings(): ?int {
        return $this->numberOfGuidanceMeetings;
    }

    public function setNumberOfGuidanceMeetings(int $numberOfGuidanceMeetings): void {
        $this->numberOfGuidanceMeetings = $numberOfGuidanceMeetings;
    }
}
