<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Contracts\AbstractMeeting;
use App\Entity\Traits\GradeTrait;
use App\Entity\Traits\ProjectTrait;
use App\Repository\MilestoneMeetingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: MilestoneMeetingRepository::class)]
class MilestoneMeeting extends AbstractMeeting
{
    #[ORM\ManyToOne(inversedBy: 'milestoneMeetings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    use ProjectTrait;

    use GradeTrait;
}
