<?php


namespace App\Tests\Functional\Controller;


use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RssControllerTest extends WebTestCase
{
    use FixturesTrait;

    public static function setUpBeforeClass(): void
    {
        (new self())->loadFixtures(array(
            'App\DataFixtures\FunctionalTest\RegisterFixtures',
            'App\DataFixtures\FunctionalTest\TheRegisterFeedFixtures'
        ));
    }

    /**
     * @return string
     */
    private static function getToken(): string
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

        return $responseContent['token'];
    }

    /**
     * @dataProvider \App\Tests\Functional\DataProvider\HttpMethodDataProvider::methods()
     *
     * @param string $method
     */
    public function testFeedNotAllowMethods(string $method)
    {
        $client = static::createClient();

        $client->request(
            $method,
            '/api/rss/feed',
            [],
            [],
            [],
            ''
        );

        if ($method === "GET") {
            $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
        } else if ($method === "HEAD") {
            $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
        } else {
            $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
        }
    }

    /**
     * @dataProvider \App\Tests\Functional\DataProvider\HttpMethodDataProvider::methods()
     *
     * @param string $method
     */
    public function testKeywordsNotAllowMethods(string $method)
    {
        $client = static::createClient();

        $client->request(
            $method,
            '/api/rss/keywords',
            [],
            [],
            [],
            ''
        );

        if ($method === "GET") {
            $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
        } else if ($method === "HEAD") {
            $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
        } else {
            $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
        }
    }


    public function testFeed()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/rss/feed',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'BEARER ' .  static::getToken()],
            ''
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains('{"feed":{"id":"', $client->getResponse()->getContent());
    }

}