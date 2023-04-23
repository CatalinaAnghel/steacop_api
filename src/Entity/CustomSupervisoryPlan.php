<?php

namespace App\Entity;

use App\Entity\Contracts\AbstractSupervisoryPlan;
use App\Repository\CustomSupervisoryPlanRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomSupervisoryPlanRepository::class)]
class CustomSupervisoryPlan extends AbstractSupervisoryPlan
{
    #[ORM\OneToOne(inversedBy: 'supervisoryPlan', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
