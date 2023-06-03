<?php
declare(strict_types=1);

namespace App\Dto\Functionality\Output;

use App\Dto\Traits\IdentityTrait;

class BaseFunctionalityOutputDto
{
    use IdentityTrait;

    /**
     * @var string $code
     */
    private string $code;

    /**
     * @var string $title
     */
    private string $title;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
