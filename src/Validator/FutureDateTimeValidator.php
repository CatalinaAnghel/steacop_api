<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\ConstraintValidator;

class FutureDateTimeValidator extends ConstraintValidator
{
    /**
     * @param \DateTime $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        /* @var FutureDateTime $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $minDate = (new \DateTime('NOW'))?->modify('+1 day');
        if($value < $minDate){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->format('Y-m-d H:i:s'))
                ->addViolation();
        }
    }
}
