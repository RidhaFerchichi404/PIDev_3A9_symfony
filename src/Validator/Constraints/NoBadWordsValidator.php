<?php

namespace App\Validator\Constraints;

use App\Service\BadWordFilter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NoBadWordsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly BadWordFilter $badWordFilter
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof NoBadWords) {
            throw new UnexpectedTypeException($constraint, NoBadWords::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->badWordFilter->containsBadWords($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
} 