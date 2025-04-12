<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class NoBadWords extends Constraint
{
    public string $message = 'Your comment contains inappropriate language. Please revise your comment.';
} 