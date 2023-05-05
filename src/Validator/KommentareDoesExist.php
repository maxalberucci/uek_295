<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class KommentareDoesExist extends Constraint
{
    public string $message = "Der Kommentar mit ID {{kommentareId}} existier nicht";
}