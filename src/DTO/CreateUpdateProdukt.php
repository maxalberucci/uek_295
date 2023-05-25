<?php

namespace App\DTO;

class CreateUpdateProdukt
{

    public ?string $Name = null;


    public ?float $Preis = null;

    private ?int $Bestand = null;
}