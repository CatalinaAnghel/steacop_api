<?php
declare(strict_types=1);

namespace App\Validator\Contracts;

interface ValidatorInterface {
    public function validate($data, $referencedObject = null): void;
}
