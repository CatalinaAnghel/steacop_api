<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SystemModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: SystemModuleRepository::class)]
class SystemModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: SystemVariable::class, orphanRemoval: true)]
    private Collection $systemVariables;

    public function __construct()
    {
        $this->systemVariables = new ArrayCollection();
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
     * @return Collection<int, SystemVariable>
     */
    public function getSystemVariables(): Collection
    {
        return $this->systemVariables;
    }

    public function addSystemVariable(SystemVariable $systemVariable): self
    {
        if (!$this->systemVariables->contains($systemVariable)) {
            $this->systemVariables->add($systemVariable);
            $systemVariable->setModule($this);
        }

        return $this;
    }

    public function removeSystemVariable(SystemVariable $systemVariable): self
    {
        if ($this->systemVariables->removeElement($systemVariable)) {
            // set the owning side to null (unless already changed)
            if ($systemVariable->getModule() === $this) {
                $systemVariable->setModule(null);
            }
        }

        return $this;
    }
}
