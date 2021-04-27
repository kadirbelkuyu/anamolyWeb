<?php

namespace GingerPayments\Payment;

use Assert\Assertion as Guard;
use Httpful\Request;

final class Ginger
{
    /**
     * The library version.
     */
    const CLIENT_VERSION = '1.5.1';

    /**
     * The API version.
     */
    const API_VERSION = 'v1';

    /**
     * Minimal PHP version required to run the library.
     */
    const MIN_PHP_VERSION = '5.4.0';

    /**
     * Create a new API client.
     *
     * @param string $apiKey Your API key.
     * @param string $product PSP product
     * @return Client
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public static function createClient($apiKey, $product = null)
    {
        Guard::uuid(
            static::apiKeyToUuid($apiKey),
            'ING API key is invalid: '.$apiKey
        );

        if (!static::validPHPVersion()) {
            throw new \Exception('Minimum required PHP version must be '.MIN_PHP_VERSION.' or above.');
        }

        return new Client(
            strtr(
                static::getEndpoint($product),
                ['{version}' => self::API_VERSION]
            ),
            Request::init()
                ->authenticateWith($apiKey, '')
                ->addHeaders([
                    'User-Agent' => 'ing-php/'.self::CLIENT_VERSION,
                    'X-PHP-Version' => PHP_VERSION
                ])
        );
    }

    /**
     * Get API endpoint based on product
     *
     * @param string $product
     * @return string
     */
    public static function getEndpoint($product)
    {
        switch ($product) {
            case 'kassacompleet':
                return (new Client\EndpointResolver())->getEndpointKassa();
            case 'ingcheckout':
                return (new Client\EndpointResolver())->getEndpointIng();
            case 'epay':
                return (new Client\EndpointResolver())->getEndpointEpay();
            default:
                return (new Client\EndpointResolver())->getEndpointGinger();
        }
    }

    /**
     * Method restores dashes in Ginger API key in order to validate UUID.
     *
     * @param string $apiKey
     * @return string UUID
     */
    public static function apiKeyToUuid($apiKey)
    {
        return preg_replace('/(\w{8})(\w{4})(\w{4})(\w{4})(\w{12})/', '$1-$2-$3-$4-$5', $apiKey);
    }

    /**
     * Check for minimal required PHP version.
     *
     * @return bool
     */
    public static function validPHPVersion()
    {
        return (bool) version_compare(PHP_VERSION, static::MIN_PHP_VERSION, '>=');
    }
}
