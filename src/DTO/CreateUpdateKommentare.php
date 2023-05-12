<?php

namespace App\DTO;

use App\Validator\ProduktDoesExist;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUpdateKommentare
{
    #[Assert\NotBlank (message: "kommentar darf nicht leer sein." , groups: ["create"])]
    public ?string $kommentare = null;

    #[Assert\NotBlank (message: "rezension darf nicht leer sein." , groups: ["create","update"])]
    public ?int $rezensionen = null;

    #[ProduktDoesExist(groups: ["create"])]
    #[Assert\NotBlank (message: "Produkt darf nicht leer sein", groups: ["create"])]
    public ?int $produkt_id = null;
}