<?php
declare(strict_types=1);

namespace App\Dto\Rating\Input;

use Symfony\Component\Validator\Constraints as Assert;

class RatingInputDto
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\LessThanOrEqual(5)]
    private float $value;

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
}
