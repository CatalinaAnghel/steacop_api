<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait GradeTrait
{
    #[ORM\Column(nullable: true)]
    private ?float $grade = null;

    public function getGrade(): ?float
    {
        return $this->grade;
    }

    public function setGrade(?float $grade): self
    {
        $this->grade = $grade;

        return $this;
    }
}
