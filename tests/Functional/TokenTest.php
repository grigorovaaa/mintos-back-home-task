<?php


namespace App\Tests\Functional\Controller;


use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TokenTest extends WebTestCase
{
    use FixturesTrait;

    public static function setUpBeforeClass(): void
    {
        (new self())->loadFixtures(array(
            'App\DataFixtures\FunctionalTest\RegisterFixtures'
        ));
    }

    public function testTokenRefresh()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/token/refresh',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['refresh_token' => static::getRefreshToken()])
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains('{"token":"', $client->getResponse()->getContent());
    }

    /**
     * @return string
     */
    private static function getRefreshToken(): string
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => 'admin@test.ru', 'password' => '0000'])
        );

        $responseContent = json_decode($client->getResponse()->getContent(), true);

        return $responseContent['refresh_token'];
    }

}