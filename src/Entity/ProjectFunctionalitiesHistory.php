<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\ProjectFunctionalitiesHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectFunctionalitiesHistoryRepository::class)]
#[ApiResource]
class ProjectFunctionalitiesHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'history')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'projectFunctionalitiesHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FunctionalityStatus $status = null;

    #[ORM\Column]
    private ?int $numberOfFunctionalities = null;

    use TimestampableTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getStatus(): ?FunctionalityStatus
    {
        return $this->status;
    }

    public function setStatus(?FunctionalityStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getNumberOfFunctionalities(): ?int
    {
        return $this->numberOfFunctionalities;
    }

    public function setNumberOfFunctionalities(int $numberOfFunctionalities): self
    {
        $this->numberOfFunctionalities = $numberOfFunctionalities;

        return $this;
    }
}
