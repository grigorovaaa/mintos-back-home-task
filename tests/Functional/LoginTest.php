<?php


namespace App\Tests\Functional;


use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Generator;


class LoginTest extends WebTestCase
{
    use FixturesTrait;

    public static function setUpBeforeClass(): void
    {
        (new self())->loadFixtures(array(
            'App\DataFixtures\FunctionalTest\RegisterFixtures'
        ));
    }

    /**
     * @dataProvider dataProviderLogin
     * @param string $email
     * @param string $password
     * @param int $responseCode
     * @param string $responseMessage
     */
    public function testLogin(string $email, string $password, int $responseCode, string $responseMessage)
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => $email, 'password' => $password])
        );

        $this->assertEquals($responseCode, $client->getResponse()->getStatusCode());
        $this->assertContains($responseMessage, $client->getResponse()->getContent());
    }

    /**
     * @return Generator
     */
    public function dataProviderLogin(): Generator
    {
        yield ['not_exists@mail.ru', '0000', Response::HTTP_UNAUTHORIZED, '{"code":401,"message":"Invalid credentials."}'];
        // exists in fixture
        yield ['admin@test.ru', '0000', Response::HTTP_OK, '{"token":"'];
    }
}