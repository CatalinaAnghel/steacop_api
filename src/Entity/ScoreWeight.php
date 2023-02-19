<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ScoreWeightRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ScoreWeightRepository::class)]
class ScoreWeight {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $weight = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function getWeight(): ?float {
        return $this->weight;
    }

    public function setWeight(float $weight): void {
        $this->weight = $weight;
    }
}
