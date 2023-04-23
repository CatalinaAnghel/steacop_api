<?php
declare(strict_types=1);

namespace App\Dto\Rating\Output;

class RatingOutputDto
{
    private int $id;

    private int $guidanceMeetingId;

    private string $userCode;

    private float $value;

    /**
     * @return int
     */
    public function getGuidanceMeetingId(): int
    {
        return $this->guidanceMeetingId;
    }

    /**
     * @param int $guidanceMeetingId
     */
    public function setGuidanceMeetingId(int $guidanceMeetingId): void
    {
        $this->guidanceMeetingId = $guidanceMeetingId;
    }

    /**
     * @return string
     */
    public function getUserCode(): string
    {
        return $this->userCode;
    }

    /**
     * @param string $userCode
     */
    public function setUserCode(string $userCode): void
    {
        $this->userCode = $userCode;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
