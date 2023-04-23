<?php

namespace App\Entity\Traits;

use App\Entity\SupervisoryPlan;
use Doctrine\ORM\Mapping as ORM;

trait PersonTrait
{
    #[ORM\Column(length: 64)]
    private ?string $firstName = null;

    #[ORM\Column(length: 64)]
    private ?string $lastName = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SupervisoryPlan $supervisoryPlan = null;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return SupervisoryPlan|null
     */
    public function getSupervisoryPlan(): ?SupervisoryPlan
    {
        return $this->supervisoryPlan;
    }

    /**
     * @param SupervisoryPlan|null $supervisoryPlan
     */
    public function setSupervisoryPlan(?SupervisoryPlan $supervisoryPlan): void
    {
        $this->supervisoryPlan = $supervisoryPlan;
    }

    //    public function getUser(): ?User {
    //        return $this->user;
    //    }
    //
    //    public function setUser(User $user): self {
    //        $this->user = $user;
    //
    //        return $this;
    //    }
}
