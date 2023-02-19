<?php
declare(strict_types=1);

namespace App\Dto\ScoreWeight\Output;

class ScoreWeight {
    /**
     * @var int $id
     */
    protected int $id;

    /**
     * @var string $name
     */
    protected string $name;

    /**
     * @var float $weight
     */
    protected float $weight;

    /**
     * @var string $description
     */
    protected string $description;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getWeight(): float {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight(float $weight): void {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }
}
