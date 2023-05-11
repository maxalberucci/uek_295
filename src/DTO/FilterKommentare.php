<?php

namespace App\DTO;

class FilterKommentare
{
    public ?string $kommentare = null;

    public ?int $rezensionen = null;
    public ?string $orderby = null;
    public ?string $orderdirection = null;
}