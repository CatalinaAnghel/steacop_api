<?php
declare(strict_types=1);

namespace App\Dto\Meeting\Contracts;

use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractMeetingInputDto {
    #[Assert\Length(min: 16, minMessage: "The description should have more than 16 characters")]
    private string $description;

    private ?string $link;

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

    /**
     * @return string|null
     */
    public function getLink(): ?string {
        return $this->link;
    }

    /**
     * @param string|null $link
     */
    public function setLink(?string $link): void {
        $this->link = $link;
    }
}
