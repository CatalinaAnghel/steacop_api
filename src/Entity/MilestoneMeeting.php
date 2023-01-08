<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MilestoneMeetingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: MilestoneMeetingRepository::class)]
class MilestoneMeeting extends Meeting {
    #[ORM\Column(nullable: true)]
    private ?float $grade = null;

    public function __construct() {
        parent::__construct();
    }

    public function getGrade(): ?float {
        return $this->grade;
    }

    public function setGrade(?float $grade): self {
        $this->grade = $grade;

        return $this;
    }
}
