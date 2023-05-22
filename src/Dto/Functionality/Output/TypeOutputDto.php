<?php
declare(strict_types=1);

namespace App\Dto\Functionality\Output;

use App\Dto\Traits\IdentityTrait;

class TypeOutputDto
{
    use IdentityTrait;

    /**
     * @var string $name
     */
    private string $name;

    /**
     * @var int[] $possibleChildTypes
     */
    private array $possibleChildTypes;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getPossibleChildTypes(): array
    {
        return $this->possibleChildTypes;
    }

    /**
     * @param array $possibleChildTypes
     */
    public function setPossibleChildTypes(array $possibleChildTypes): void
    {
        $this->possibleChildTypes = $possibleChildTypes;
    }
}
