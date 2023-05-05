<?php

namespace App\DTO;

class ShowProdukt
{
    public ?string $name = null;
    public ?float $preis = null;
    public ?int $bestand = null;

    public  $kommentare = [];
}