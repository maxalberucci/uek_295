<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ProduktDoesExist extends Constraint
{
    public string $message = "Das Produkt mit ID {{produktId}} existiert nicht";
}