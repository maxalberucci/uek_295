<?php

namespace App\Controller\DTO\Mapper;

use App\Controller\DTO\ShowKommentare;
use App\DTO\Mapper\BaseMapper;

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