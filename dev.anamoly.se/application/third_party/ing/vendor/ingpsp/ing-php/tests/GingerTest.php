<?php

namespace GingerPayments\Payment\Tests;

use GingerPayments\Payment\Ginger;
use GingerPayments\Payment\Client\EndpointResolver;

final class GingerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldCreateAClient()
    {
        $reflectionClass = new \ReflectionClass('GingerPayments\Payment\Client');
        $reflectionProperty = $reflectionClass->getProperty('httpClient');
        $reflectionProperty->setAccessible(true);

        $client = Ginger::createClient('f47ac10b58cc4372a5670e02b2c3d479');

        /** @var Httpful\Request $httpClient */
        $httpClient = $reflectionProperty->getValue($client);
        $this->assertEquals(
            $httpClient->headers,
            [
                'User-Agent' => 'ing-php/'.Ginger::CLIENT_VERSION,
                'X-PHP-Version' => PHP_VERSION
            ]
        );
        $this->assertEquals($httpClient->username, 'f47ac10b58cc4372a5670e02b2c3d479');
        $this->assertEquals($httpClient->password, '');
    }

    /**
     * @test
     */
    public function itShouldFailWithIncorrectAPIkey()
    {
        $this->setExpectedException('Assert\InvalidArgumentException');
        Ginger::createClient('my-api-key');
    }

    /**
     * @test
     */
    public function itShouldCreateAValidUuid()
    {
        $this->assertEquals(
            Ginger::apiKeyToUuid('f47ac10b58cc4372a5670e02b2c3d479'),
            'f47ac10b-58cc-4372-a567-0e02b2c3d479'
        );
    }
}
