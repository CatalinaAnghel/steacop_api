<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Contracts\AbstractMeeting;
use App\Entity\Traits\ProjectTraits;
use App\Repository\MilestoneMeetingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: MilestoneMeetingRepository::class)]
class MilestoneMeeting extends AbstractMeeting {
    #[ORM\Column(nullable: true)]
    private ?float $grade = null;

    #[ORM\ManyToOne(inversedBy: 'milestoneMeetings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    use ProjectTraits;

    public function getGrade(): ?float {
        return $this->grade;
    }

    public function setGrade(?float $grade): void {
        $this->grade = $grade;
    }
}
