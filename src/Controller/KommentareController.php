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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route("/api", name: "api_")]
class KommentareController extends AbstractFOSRestController
{

    private function validateDTO($dto, $group) {
        $erros = $this->validator->validate($dto, groups: ["create"]);

        if ($erros->count() > 0){
            $errosStringArray = [];
            foreach ($erros as $error){
                $errosStringArray[] = $error->getMessage();
            }
            return $this->json($errosStringArray, status: 400);
        }
    }

    public function __construct(private SerializerInterface $serializer,
                                private KommentareRepository $repository,
                                private ShowKommentareMapper $mapper,
                                private ProduktRepository $pRepository,
                                private ValidatorInterface $validator){}


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
        content:
        new JsonContent(
            type: 'array',
            items: new Items(
                ref: new Model(
                    type: ShowKommentare::class
                )
            )
        )
    )]

    #[Rest\Get('/kommentare', name: 'app_kommentare_get')]
    public function kommentare_get(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/KommentareController.php',
        ]);
    }

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
    #[Rest\Delete('/kommentare/{id}', name: 'app_kommentare_delete')]
    public function kommentare_delete($id): JsonResponse
    {
        $entityToDelete = $this->repository->find($id);

        if(!$entityToDelete) {
            return $this->json("Kommentar mit ID {$id} wurde nicht gefunden");
        }

        $this->repository->remove($entityToDelete, true);

        return $this->json("{$entityToDelete->getKommentare()} wurde erfolgreich gelöscht");
    }
    #[Rest\Put('/kommentare/{id}', name: 'app_kommentare_update')]
    public function kommentare_update($id, Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateKommentare::class, "json");

        $entityToUpdate = $this->repository->find($id);

        if(!$entityToUpdate) {return $this->json("Kommentar mit ID {$id} wurde nicht gefunden.");}


        $errorResponse = $this->validateDTO($dto, "update");

        if($errorResponse) {return $errorResponse;}

        $entityToUpdate->setKommentare($dto->kommentare);


        return (new JsonResponse())->setContent(
            $this->serializer->serialize($this->mapper->mapEntityToDTO($entityToUpdate),"json")
        );
    }

}
