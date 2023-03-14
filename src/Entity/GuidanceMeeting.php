<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Contracts\AbstractMeeting;
use App\Entity\Traits\ProjectTraits;
use App\Repository\GuidanceMeetingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ApiResource]
#[ORM\Entity(repositoryClass: GuidanceMeetingRepository::class)]
class GuidanceMeeting extends AbstractMeeting {
    #[ORM\OneToMany(mappedBy: 'meeting', targetEntity: Rating::class, orphanRemoval: true)]
    private Collection $ratings;

    #[ORM\ManyToOne(inversedBy: 'guidanceMeetings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[Pure] public function __construct() {
        parent::__construct();
        $this->ratings = new ArrayCollection();
    }

    use ProjectTraits;

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection {
        return $this->ratings;
    }

    public function addRating(Rating $rating): void {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setMeeting($this);
        }
    }

    public function removeRating(Rating $rating): void {
        if ($this->ratings->removeElement($rating) && $rating->getMeeting() === $this) {
            $rating->setMeeting(null);
        }
    }
}
