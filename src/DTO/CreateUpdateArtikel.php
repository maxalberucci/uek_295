<?php

namespace App\DTO;

class CreateUpdateArtikel
{

    public ?string $Name = null;


    public ?float $Preis = null;

    private ?int $Bestand = null;
}