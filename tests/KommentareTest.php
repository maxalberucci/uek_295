<?php

namespace App\Tests;

use App\DTO\CreateUpdateKommentare;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

class KommentareTest extends WebTestCase
{
    public static $client;
    public static $application;

    public static function setUpBeforeClass(): void
    {
        self::$client = new \GuzzleHttp\Client([
            "base_uri" => "http://localhost:8000/index_test.php"
            ]);

    $client = self::createClient();
    self::$application = new Application($client->getKernel());
    self::$application->setAutoExit(false);

    self::$application->run(new StringInput("doctrine:database:drop --force"));
    self::$application->run(new StringInput("doctrine:database:create"));
    self::$application->run(new StringInput("doctrine:schema:create"));
    self::$application->run(new StringInput("doctrine:fixtures:load"));
}

    public function testPost() {
        $dto = new CreateUpdateKommentare();
            $dto->kommentare = "Test";

            $this->expectException(ClientException::class);

        $request = self::$client->request("POST", "api/login_check",
            [
                "body" => json_encode([
                    "username" => "Luca Moser",
                    "password" => "test"
                ])
            ]);

        $token = json_decode($request->getBody())->token;

        $request = self::$client->request("POST", "api/kommentare",
            [
                "body" => json_encode($dto),
                "headers" => [
                    "Authorization" => "Bearer " . $token
                ]
            ]);


            $response = json_decode($request->getBody());

            $this->assertTrue($request->getStatusCode() == 200);
            $this->assertTrue($response->kommentare == "Test");
    }

    public function testLoadKommentare()
    {

        $request = self::$client->request('GET', '/api/kommentare');

        $this->assertTrue($request->getStatusCode() == 200);

        $this->assertIsArray(json_decode($request->getBody()));
    }


    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function TestGetKommentare()
    {
        $request = self::$client->request('GET', 'api/kommentare');

        $this->assertTrue(200 == $request->getStatusCode());
    }

    public function TestGetProdukt()
    {
        $request = self::$client->request('GET', 'api/produkt');

        $this->assertTrue(200 == $request->getStatusCode());
    }

    public function TestPutKommentare(): void
    {
        $updatedDto = new CreateUpdateKommentare();
        $updatedDto->kommentare = 'Neuer Kommentar';
        $updatedDto->rezensionen = 1;
        $updatedDto->produkt_id = 1;

        $putRequest = self::$client->request('PUT', 'api/kommentare/1', [
            'body' => json_encode($updatedDto),
        ]);

        $this->assertTrue(200 == $putRequest->getStatusCode());
    }
}
