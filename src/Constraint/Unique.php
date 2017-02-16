<?php

namespace BlogWriter\Constraint;

use Symfony\Component\Validator\Constraint;

class Unique extends Constraint {

    public $notUniqueMessage = '%string% est déjà utilisé.';
    public $entity;
    public $field;

    public function validatedBy()
    {
        return 'validator.unique';
    }
}