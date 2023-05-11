<?php

namespace App\Controller;

use App\DTO\CreateUpdateKommentare;
use App\DTO\FilterKommentare;
use App\DTO\Mapper\ShowKommentareMapper;
use App\DTO\ShowKommentare;
use App\Entity\Kommentare;
use App\Repository\KommentareRepository;
use App\Repository\ProduktRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route("/api", name: "api_")]
class KommentareController extends AbstractFOSRestController
{

    /**
     * Validiert die datenübertragung
     */
    private function validateDTO($dto, $group)
    {
        $erros = $this->validator->validate($dto, groups: ["create"]);


        /**
         * Validierungsfehler mit JSON-Antwort der fehler ausgeben
         */
        if ($erros->count() > 0) {
            $errosStringArray = [];
            foreach ($erros as $error) {
                $errosStringArray[] = $error->getMessage();
            }
            return $this->json($errosStringArray, status: 400);
        }
    }

    /**
     * Wichtige Injects
     * @param SerializerInterface $serializer
     * @param KommentareRepository $repository
     * @param ShowKommentareMapper $mapper
     * @param ProduktRepository $pRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(private SerializerInterface  $serializer,
                                private KommentareRepository $repository,
                                private ShowKommentareMapper $mapper,
                                private ProduktRepository    $pRepository,
                                private ValidatorInterface   $validator,
                                private LoggerInterface      $logger)
    {
    }


    /**
     * Ruft die Kommentare und zugehörigen Produkte auf
     * @return JsonResponse
     */
    #[Get(requestBody: new RequestBody(
        content: new JsonContent(
            ref: new Model(
                type: FilterKommentare::class
            )
        )
    ))]
    #[\OpenApi\Attributes\Response(
        response: 200,
        description: "gibt alle Kommentare inklusive deren Produkte an",
        content: new JsonContent(
            type: 'array',
            items: new Items(
                ref: new Model(
                    type: ShowKommentare::class
                )
            )
        )
    )]
    #[Rest\Get('/kommentare', name: 'app_kommentare_get')]
    public function kommentare_get(Request $request): JsonResponse
    {
        $this->logger->info("Kommentare get");

        $dtoFilter = $this->serializer->deserialize(
            $request->getContent(),
            FilterKommentare::class, 'json'
        );

        $allProdukte = $this->repository->filterAll($dtoFilter) ?? [];


        return (new JsonResponse())->setContent(
            $this->serializer->serialize(
                $this->mapper->mapEntitiesToDTOS($allProdukte),"json")
        );
    }

    /**
     * erstellen eines neuen Kommentar
     * @param Request $request
     * @return JsonResponse
     */
    #[Post(
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: new Model(
                    type: CreateUpdateKommentare::class,
                    groups: ("create")
                )
            )
        )
    )]



    #[Rest\Post('/kommentare', name: 'app_kommentare_create')]
    public function kommentare_create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateKommentare::class, "json");

        $errorResponse = $this->validateDTO($dto, "create");

        if($errorResponse) {return $errorResponse;}

        $entity = new Kommentare();
        $entity->setKommentare($dto->kommentare);
        $entity->setRezensionen($dto->rezensionen);

        $produkt = $this->pRepository->find($dto->produkt_id);
        $entity->setProdukt($produkt);


        $this->repository->save($entity, true);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize($this->mapper->mapEntityToDTO($entity),"json")
        );
    }

    /**
     * Kommentar anhand von ID gelöscht
     * @param $id
     * @return JsonResponse
     */
    #[Rest\Delete('/kommentare/{id}', name: 'app_kommentare_delete')]
    public function kommentare_delete($id): JsonResponse
    {
        /**
         * findet die entity die gelöscht werden sollte
         */
        $entityToDelete = $this->repository->find($id);

        /**
         * überprüft ob entity gefunden wurde
         */
        if(!$entityToDelete) {
            return $this->json("Kommentar mit ID {$id} wurde nicht gefunden");
        }

        $this->repository->remove($entityToDelete, true);

        /**
         * Nachricht falls löschen erfolgreich war
         */
        return $this->json("{$entityToDelete->getKommentare()} wurde erfolgreich gelöscht");
    }

    /**
     * Mit ID ändern des kommentar
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Rest\Put('/kommentare/{id}', name: 'app_kommentare_update')]
    public function kommentare_update($id, Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateKommentare::class, "json");

        /**
         * finden durch ID entity die aktualisiert wird
         */
        $entityToUpdate = $this->repository->find($id);

        /**
         * überprüft ob entity gefunden worden ist
         */
        if(!$entityToUpdate) {return $this->json("Kommentar mit ID {$id} wurde nicht gefunden.");}


        $errorResponse = $this->validateDTO($dto, "update");

        if($errorResponse) {return $errorResponse;}

        $entityToUpdate->setKommentare($dto->kommentare);


        return (new JsonResponse())->setContent(
            $this->serializer->serialize($this->mapper->mapEntityToDTO($entityToUpdate),"json")
        );
    }

}
