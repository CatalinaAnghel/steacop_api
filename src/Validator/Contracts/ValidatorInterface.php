<?php
declare(strict_types=1);

namespace App\Validator\Contracts;

interface ValidatorInterface
{
    /**
     * @param $data
     * @param $referencedObject
     * @param string|null $operation
     * @return void
     */
    public function validate($data, $referencedObject = null, string $operation = null): void;
}
