<?php

namespace BlogWriter\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueValidator extends ConstraintValidator {

    private $app;

    public function __construct($app) {
        $this->app = $app;
    }

    public function validate($value, Constraint $constraint)
    {
        $exists = $this->app['dao.' . $constraint->entity ]->findOneBy(array($constraint->field, $value));

        if ($exists)
        {
            $this->context->addViolation($constraint->notUniqueMessage, array('%string%' => $value));

            return false;
        }

        return true;
    }
}