<?php

namespace App\DTO;

class FilterProdukt
{
    public ?string $name = null;
    public ?float $preis = null;
    public ?int $bestand = null;

    public  $kommentare = [];
}