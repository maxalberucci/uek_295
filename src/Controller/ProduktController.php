<?php

namespace App\Controller;

use App\Repository\ProduktRepository;
use http\Env\Request;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api", name: "api_")]
class ProduktController extends AbstractFOSRestController
{
    public function __construct(private SerializerInterface $serializer,
                                private ProduktRepository $repository,
                                protected ValidatorInterface $validator){

    }

    #[Rest\Get('/produkt', name: 'app_produkt')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => '1!',
            'path' => 'src/Controller/ProduktController.php',
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), get_class());
        $erros = $this->validator->validate($dto, groups: ["create"]);

        if ($erros->count() > 0){
            $errosStringArray = [];
            foreach ($erros as $error){
                $errosStringArray[] = $error->getMessage();
            }
            return $this->json($errosStringArray, status: 400);
        }
    }
}