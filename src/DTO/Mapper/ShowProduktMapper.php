<?php

namespace App\DTO\Mapper;

use App\DTO\ShowProdukt;

class ShowProduktMapper extends BaseMapper
{

    public function mapEntityToDTO(object $entity)
    {
        $mapper = new ShowKommentareMapper();
        $dto = new ShowProdukt();
        $dto->name = $entity->getName();
        $dto->bestand = $entity->getBestand();
        $dto->preis = $entity->getPreis();
        $dto->kommentare = $mapper->mapEntitiesToDTOS($entity->getKommentare());

        return $dto;
    }
}