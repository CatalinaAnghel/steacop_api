<?php
declare(strict_types=1);

namespace App\Dto\User\Output;

final class UserDto
{
    /**
     * @var string $code
     */
    private string $code;

    /**
     * @var string $email
     */
    private string $email;


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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
