<?php
declare(strict_types=1);

namespace App\Entity\Contracts;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass]
abstract class AbstractSupervisoryPlan {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(10)]
    private ?int $numberOfAssignments = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(10)]
    private ?int $numberOfGuidanceMeetings = null;

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getId(): ?int {
        return $this->id;
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
