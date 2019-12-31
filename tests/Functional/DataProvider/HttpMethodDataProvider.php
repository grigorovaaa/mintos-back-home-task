<?php


namespace App\Tests\Functional\DataProvider;


use Generator;

class HttpMethodDataProvider
{
    /**
     * @return Generator
     */
    public static function methods(): Generator
    {
        yield ['HEAD'];
        yield ['PUT'];
        yield ['DELETE'];
        yield ['TRACE'];
        yield ['OPTIONS'];
        yield ['CONNECT'];
        yield ['PATCH'];
        yield ['GET'];
        yield ['POST'];
    }
}