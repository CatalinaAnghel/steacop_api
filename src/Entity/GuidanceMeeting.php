<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GuidanceMeetingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ApiResource]
#[ORM\Entity(repositoryClass: GuidanceMeetingRepository::class)]
class GuidanceMeeting extends Meeting {
    #[ORM\OneToMany(mappedBy: 'meeting', targetEntity: Rating::class, orphanRemoval: true)]
    private Collection $ratings;

    #[Pure] public function __construct() {
        parent::__construct();
        $this->ratings = new ArrayCollection();
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setMeeting($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getMeeting() === $this) {
                $rating->setMeeting(null);
            }
        }

        return $this;
    }
}
