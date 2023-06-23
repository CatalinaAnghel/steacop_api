<?php
declare(strict_types=1);

namespace App\Dto\Meeting\Contracts;

use App\Dto\Traits\IsMissedTrait;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractMeetingInputDto
{
    #[Assert\Length(min: 16, max:255, minMessage: "The description should at least 16 characters", maxMessage: "The description should have at most 255 characters")]
    private string $description;

    private ?string $link;

    use IsMissedTrait;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }
}
