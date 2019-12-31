<?php


namespace App\Tests\Functional\Controller;


use Generator;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class RegistrationControllerTest extends WebTestCase
{
    use FixturesTrait;


     /**
     * @dataProvider \App\Tests\Functional\Controller\DataProvider\HttpMethodDataProvider::methods()
     * @param string $method
     */
    public function testNotAllowMethods(string $method)
    {
        $client = static::createClient();

        $client->request(
            $method,
            '/api/registration',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'not_exists@mail.ru', 'password' => '0000'])
        );

        if ($method === "POST") {
            $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        } else {
            $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
        }
    }

    /**
     * @dataProvider dataProviderRegistration
     * @param $email
     * @param $password
     * @param $responseCode
     * @param $responseMessage
     */
    public function testRegistration($email, $password, $responseCode, $responseMessage)
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/registration',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => $email, 'password' => $password])
        );

        $this->assertEquals($responseCode, $client->getResponse()->getStatusCode());
        $this->assertEquals($responseMessage, $client->getResponse()->getContent());
    }

    /**
     * @return Generator
     */
    public function dataProviderRegistration(): Generator
    {
        $this->loadFixtures(array(
            'App\DataFixtures\FunctionalTest\RegisterFixtures'
        ));

        // todo parse str to arrays
        yield [null, null, Response::HTTP_CONFLICT, '{"message":"email cannot be null"}'];
        yield ['test@mail.ru', null, Response::HTTP_CONFLICT, '{"message":"password cannot be null"}'];
        // exists in fixture
        yield ['admin@test.ru', '0000', Response::HTTP_CONFLICT, '{"message":"User already exists"}'];
        yield ['test@mail.ru', '0000', Response::HTTP_CREATED, '{}'];
        // because of previous dataset
        yield ['test@mail.ru', '0000', Response::HTTP_CONFLICT, '{"message":"User already exists"}'];
    }
}