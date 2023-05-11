<?php

namespace App\DTO\Mapper;

use App\DTO\ShowKommentare;
use OpenApi\Attributes\Property;

class ShowKommentareMapper extends BaseMapper
{


    public function mapEntityToDTO(object $entity)
    {
        $dto = new ShowKommentare();
        $dto->kommentare = $entity->getKommentare();
        $dto->rezensionen = $entity->getRezensionen();


        return $dto;
    }
}