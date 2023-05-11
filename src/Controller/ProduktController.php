<?php

namespace App\Controller;

use App\DTO\CreateUpdateArtikel;
use App\DTO\CreateUpdateKommentare;
use App\DTO\Mapper\ShowProduktMapper;
use App\DTO\ShowProdukt;
use App\Entity\Kommentare;
use App\Entity\Produkt;
use App\Repository\ProduktRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api", name: "api_")]
class ProduktController extends AbstractFOSRestController
{
    public function __construct(private SerializerInterface  $serializer,
                                private ProduktRepository    $repository,
                                protected ValidatorInterface $validator,
                                private ShowProduktMapper    $mapper){

    }

    /**
     * antwortet mit einer gescheiten antwort.
     * @return JsonResponse
     */
    #[Rest\Get('/produkt', name: 'app_produkt')]
    public function getProdukte(): JsonResponse
    {
        /**
         * alle Produkte abrufen
         */
        $allProdukte = $this->repository->findAll();


        // Produkt als JSON zurückgeben

        return (new JsonResponse())->setContent(
            $this->serializer->serialize($this->mapper->mapEntitiesToDTOS($allProdukte),"json")
        );
    }

    public function create(\Symfony\Component\HttpFoundation\Request $request): JsonResponse
    {
        /**
         * erstellen eines neuen Produktes
         */
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateArtikel::class, 'json');
        $erros = $this->validator->validate($dto, groups: ["create"]);

        /**
         * fehlermeldung zurückgeben bei fehler
         */
        if ($erros->count() > 0){
            $errosStringArray = [];
            foreach ($erros as $error){
                $errosStringArray[] = $error->getMessage();
            }
            return $this->json($errosStringArray, status: 400);
        }
    }

    #[Rest\Post('/produkt', name: 'app_produkt_create')]
    public function produkt_create(\Symfony\Component\HttpFoundation\Request $request): JsonResponse
    {
        /**
         * Produk erstellen
         */
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateKommentare::class, "json");

        $errorResponse = $this->validateDTO($dto, "create");

        /**
         * fehlermeldung bei fehler
         */
        if($errorResponse) {return $errorResponse;}

        /**
         * neue produkt entity erstellen
         */
        $entity = new Produkt();
        $entity->setName($dto->name);
        $entity->setBestand($dto->rezensionen);
        $produkt = $this->pRepository->find($dto->produkt_id);

        $entity->setProdukt($produkt);

        $this->repository->save($entity, true);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize($this->mapper->mapEntityToDTO($entity),"json")
        );
    }

    #[Rest\Get('/produkt/{id}', name: 'app_produkt_show')]
    public function show($id): JsonResponse
    {
        /**
         * anhand ID Produkt abrufen
         */
        $produkt = $this->repository->find($id);

        /**
         * Produkt nicht gefunden Fehlermeldung
         */
        if (!$produkt) {
            return $this->json(['message' => 'Product not found'], status: 404);
        }

        return (new JsonResponse())->setContent(
            $this->serializer->serialize($this->mapper->mapEntityToDTO($produkt),"json")
        );
    }

}

