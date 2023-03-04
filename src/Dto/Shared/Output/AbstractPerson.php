<?php
declare(strict_types=1);

namespace App\Dto\Shared\Output;

use App\Dto\Traits\IdentityTrait;
use App\Dto\Traits\UserTrait;

abstract class AbstractPerson {
    use IdentityTrait;
    use UserTrait;

    /**
     * @var string $firstName
     */
    private string $firstName;

    /**
     * @var string $lastName
     */
    private string $lastName;

    /**
     * @var string $phoneNumber
     */
    private string $phoneNumber;

    /**
     * @return string
     */
    public function getFirstName(): string {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void {
        $this->phoneNumber = $phoneNumber;
    }
}
