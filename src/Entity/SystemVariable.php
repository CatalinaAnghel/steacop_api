<?php

namespace App\Entity;

use App\Repository\SystemVariableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemVariableRepository::class)]
class SystemVariable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'systemVariables')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SystemModule $module = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 64)]
    private ?string $value = null;

    #[ORM\Column]
    private ?bool $isEditable = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModule(): ?SystemModule
    {
        return $this->module;
    }

    public function setModule(?SystemModule $module): self
    {
        $this->module = $module;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function isIsEditable(): ?bool
    {
        return $this->isEditable;
    }

    public function setIsEditable(bool $isEditable): self
    {
        $this->isEditable = $isEditable;

        return $this;
    }
}
