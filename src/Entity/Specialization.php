<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SpecializationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecializationRepository::class)]
#[ApiResource]
class Specialization {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'specializations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Department $department = null;

    #[ORM\OneToMany(mappedBy: 'specialization', targetEntity: Student::class, orphanRemoval: true)]
    private Collection $students;

    public function __construct() {
        $this->students = new ArrayCollection();
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

    public function getDepartment(): ?Department {
        return $this->department;
    }

    public function setDepartment(?Department $department): self {
        $this->department = $department;

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
            $student->setSpecialization($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getSpecialization() === $this) {
                $student->setSpecialization(null);
            }
        }

        return $this;
    }
}
