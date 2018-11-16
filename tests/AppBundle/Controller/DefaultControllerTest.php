<?php

namespace Tests\AppBundle\Controller;

use Bazinga\Bundle\RestExtraBundle\Test\WebTestCase;
use JsonSchema\Validator;

class DefaultControllerTest extends WebTestCase
{
    protected $client;
    protected $jsonSchemaObject;
    protected $jsonValidator;

    protected static $jsonSchema;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->jsonSchemaObject = (object)['$ref' => 'file://'.realpath(__DIR__.'/schema.json')];
        $this->jsonValidator = new Validator();
    }

    public function testIndexGetHtml()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }

    public function testPingGetHtml()
    {
        $crawler = $this->client->request('GET', '/ping');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('JSON Response for', $crawler->filter('#container h1')->text());
    }

    public function testPingGetJson()
    {
        $crawler = $this->jsonRequest('GET', '/ping');
        $response = $this->client->getResponse();
        $decodedResponse = json_decode($response->getContent());
        $this->jsonValidator->validate($decodedResponse, $this->jsonSchemaObject);
        $validJson = $this->jsonValidator->isValid();
        $errorMessage = '';
        if (!$validJson)
        {
            foreach ($this->jsonValidator->getErrors() as $error) {
                $errorMessage .= sprintf("[%s] %s\n", $error['property'], $error['message']);
            }
        }

        $this->assertJsonResponse($response);
        $this->assertTrue($validJson, $errorMessage);
    }

    public function testPingPostJson()
    {
        $crawler = $this->jsonRequest('POST', '/ping', []);
        $response = $this->client->getResponse();
        $decodedResponse = json_decode($response->getContent());
        $this->jsonValidator->validate($decodedResponse, $this->jsonSchemaObject);
        $validJson = $this->jsonValidator->isValid();
        $errorMessage = '';
        if (!$validJson)
        {
            foreach ($this->jsonValidator->getErrors() as $error) {
                $errorMessage .= sprintf("[%s] %s\n", $error['property'], $error['message']);
            }
        }

        $this->assertJsonResponse($response, 405);
        $this->assertTrue($validJson, $errorMessage);
    }
}
