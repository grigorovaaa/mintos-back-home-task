<?php


namespace App\Tests\Functional\Controller;


use Generator;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class RegistrationControllerTest extends WebTestCase
{
    use FixturesTrait;

    public static function setUpBeforeClass(): void
    {
        (new self())->loadFixtures(array(
            'App\DataFixtures\FunctionalTest\RegisterFixtures'
        ));
    }

     /**
     * @dataProvider \App\Tests\Functional\DataProvider\HttpMethodDataProvider::methods()
      *
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
        } else if ($method === "HEAD") {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        } else {
            $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
        }
    }

    /**
     * @dataProvider dataProviderRegistration
     *
     * @param string|null $email
     * @param string|null $password
     * @param int $responseCode
     * @param string $responseMessage
     */
    public function testRegistration(?string $email, ?string $password, int $responseCode, string $responseMessage)
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
     * @dataProvider dataProviderRegistrationCheck
     *
     * @param string|null $email
     * @param int $responseCode
     */
    public function testRegistrationCheck(?string $email, int $responseCode)
    {
        $client = static::createClient();

        $client->request(
            'HEAD',
            "/api/registration?email={$email}",
            [],
            [],
            [],
            ''
        );

        $this->assertEquals($responseCode, $client->getResponse()->getStatusCode());
    }

    /**
     * @return Generator
     */
    public function dataProviderRegistration(): Generator
    {
        yield [null, null, Response::HTTP_CONFLICT, '{"message":"email cannot be null"}'];
        yield ['test@mail.ru', null, Response::HTTP_CONFLICT, '{"message":"password cannot be null"}'];
        // exists in fixture
        yield ['admin@test.ru', '0000', Response::HTTP_CONFLICT, '{"message":"User already exists"}'];
        yield ['test@mail.ru', '0000', Response::HTTP_CREATED, '{}'];
        // exists because of previous dataset
        yield ['test@mail.ru', '0000', Response::HTTP_CONFLICT, '{"message":"User already exists"}'];
    }

    /**
     * @return Generator
     */
    public function dataProviderRegistrationCheck(): Generator
    {
        yield [null, Response::HTTP_BAD_REQUEST];
        yield ['testcheck@mail.ru', Response::HTTP_NOT_FOUND];
        // exists in fixture
        yield ['admin@test.ru', Response::HTTP_NO_CONTENT];
    }
}