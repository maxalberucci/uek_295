<?php

namespace App\DTO\Mapper;

use App\DTO\ShowProdukt;

class ArtikelMapper extends BaseMapper
{

    public function mapEntityToDTO(object $entity)
    {
        $kommentarMapper = new ShowKommentareMapper();

        $dto = new ShowProdukt();
        $dto->name = $entity->getName();

        $dto->kommentare = $kommentarMapper->mapEntitiesToDTOS($entity->getKommentare());

        return $dto;
    }
}