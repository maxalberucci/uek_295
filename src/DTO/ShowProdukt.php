<?php

namespace App\DTO;


use Nelmio\ApiDocBundle\Model\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;

class ShowProdukt
{
    public ?string $name = null;
    public ?float $preis = null;
    public ?int $bestand = null;

    #[Property(
        "Kommentare",
        type: "array",
        items: new Items(
            ref: new Model(
                type: ShowKommentare::class
            )
        )
    )]

    public  $kommentare = [];


}