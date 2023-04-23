<?php
declare(strict_types=1);

namespace App\ApiResource\Traits;

use ApiPlatform\Metadata\ApiProperty;

trait IdentityTrait
{
    #[ApiProperty(identifier: true)]
    /**
     * @var int $id
     */
    protected int $id;

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
