<?php

namespace App\Dto\User\Input;

class UserInputDto {
    /**
     * @var string $code
     */
    private string $code;

    /**
     * @var string $firstName
     */
    private string $firstName;

    /**
     * @var string $lastName
     */
    private string $lastName;

    /**
     * @var string $email
     */
    private string $email;

    /**
     * @var string $password
     */
    private string $password;

    /**
     * @var array $roles
     */
    private array $roles;
}