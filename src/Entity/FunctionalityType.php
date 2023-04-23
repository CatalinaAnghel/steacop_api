<?php

namespace App\Entity;

use App\Repository\FunctionalityTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FunctionalityTypeRepository::class)]
class FunctionalityType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Functionality::class, orphanRemoval: true)]
    private Collection $functionalities;

    public function __construct()
    {
        $this->functionalities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Functionality>
     */
    public function getFunctionalities(): Collection
    {
        return $this->functionalities;
    }

    public function addFunctionality(Functionality $functionality): self
    {
        if (!$this->functionalities->contains($functionality)) {
            $this->functionalities->add($functionality);
            $functionality->setType($this);
        }

        return $this;
    }

    public function removeFunctionality(Functionality $functionality): self
    {
        if ($this->functionalities->removeElement($functionality) &&
            $functionality->getType() === $this
        ) {
            $functionality->setType(null);
        }

        return $this;
    }
}
