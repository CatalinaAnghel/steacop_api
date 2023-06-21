<?php
namespace App\Command\Contracts;

trait PasswordGeneratorTrait{
    public function createPassword(string $email, string $code): string{
        $password = explode('@', $email)[0] . $code;

        return $password;
    }
}