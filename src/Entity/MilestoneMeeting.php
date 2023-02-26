<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\ProjectTraits;
use App\Repository\MilestoneMeetingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: MilestoneMeetingRepository::class)]
class MilestoneMeeting extends Meeting {
    #[ORM\Column(nullable: true)]
    private ?float $grade = null;

    #[ORM\ManyToOne(inversedBy: 'milestoneMeetings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    public function __construct() {
        parent::__construct();
    }

    use ProjectTraits;

    public function getGrade(): ?float {
        return $this->grade;
    }

    public function setGrade(?float $grade): void {
        $this->grade = $grade;
    }
}
