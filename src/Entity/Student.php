<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\PersonTrait;
use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends User {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use PersonTrait;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Specialization $specialization = null;

    #[ORM\ManyToOne(inversedBy: 'student')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getSpecialization(): ?Specialization {
        return $this->specialization;
    }

    public function setSpecialization(?Specialization $specialization): self {
        $this->specialization = $specialization;

        return $this;
    }

    public function getProject(): ?Project {
        return $this->project;
    }

    public function setProject(?Project $project): self {
        $this->project = $project;

        return $this;
    }
}
