<?php

namespace GingerPayments\Payment\Client;

use Dotenv\Dotenv;

class EndpointResolver {

    /**
     * API endpoint Ginger Payments
     */
    const ENDPOINT_GINGER = 'https://api.gingerpayments.com/{version}/';

    /**
     * API Kassa Compleet endpoint
     */
    const ENDPOINT_KASSA = 'https://api.kassacompleet.nl/{version}/';

    /**
     * API endpoint ING
     */
    const ENDPOINT_ING = 'https://api.ing-checkout.com/{version}/';

    /**
     * API endpoint EPAY
     */
    const ENDPOINT_EPAY = 'https://api.epay.ing.be/{version}/';

    public function __construct() {
        try {
            $dotenv = new Dotenv(__DIR__.'/../..');
            $dotenv->load();
        } catch (\Exception $e) {
            
        }
    }

    /**
     * @return string
     */
    public function getEndpointGinger() {
        return false !== getenv('ENDPOINT_GINGER') ? getenv('ENDPOINT_GINGER') : self::ENDPOINT_GINGER;
    }

    /**
     * @return string
     */
    public function getEndpointKassa() {
        return false !== getenv('ENDPOINT_KASSA') ? getenv('ENDPOINT_KASSA') : self::ENDPOINT_KASSA;
    }

    /**
     * @return string
     */
    public function getEndpointIng() {
        return false !== getenv('ENDPOINT_ING') ? getenv('ENDPOINT_ING') : self::ENDPOINT_ING;
    }

    /**
     * @return string
     */
    public function getEndpointEpay() {
        return false !== getenv('ENDPOINT_EPAY') ? getenv('ENDPOINT_EPAY') : self::ENDPOINT_EPAY;
    }

}
