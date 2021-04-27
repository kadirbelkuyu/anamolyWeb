<?php

namespace GingerPayments\Payment;

use Httpful\Http;
use Httpful\Httpful;
use Httpful\Mime;
use Httpful\Request;

use GingerPayments\Payment\Client\ClientException;
use GingerPayments\Payment\Client\OrderNotFoundException;
use GingerPayments\Payment\Common\ArrayFunctions;
use GingerPayments\Payment\Ideal\Issuers;
use GingerPayments\Payment\Order\Transaction;

final class Client
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var Httpful\Request
     */
    private $httpClient;

    /**
     * @param string $baseUrl
     * @param Httpful\Request $template
     */
    public function __construct($baseUrl, $httpClient)
    {
        $this->baseUrl = $baseUrl;
        $this->httpClient = $httpClient;

        Httpful::register(Mime::JSON, new \Httpful\Handlers\JsonHandler(['decode_as_array' => true]));
    }

    /**
     * Set default SSL validation using cURL CA bundle.
     * http://curl.haxx.se/docs/caextract.html
     *
     * @return void
     */
    public function useBundledCA()
    {
        $this->httpClient->addOnCurlOption(
            CURLOPT_CAINFO,
            realpath(dirname(__FILE__).'/../assets/cacert.pem')
        );
    }

    /**
     * Get possible iDEAL issuers.
     *
     * @return Issuers
     */
    public function getIdealIssuers()
    {
        try {
            $response = $this->getHttpClient()
                ->method(Http::GET)
                ->uri($this->baseUrl . 'ideal/issuers/')
                ->send();

            if ($response->hasErrors()) {
                throw new \Exception($response->raw_body, $response->code);
            }

            return Issuers::fromArray($response->body);
        } catch (\Exception $exception) {
            throw new ClientException(
                'An error occurred while processing the request: '.$exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * Get allowed payment methods.
     *
     * @return array
     */
    public function getAllowedProducts()
    {
        try {
            $response = $this->getHttpClient()
                ->method(Http::GET)
                ->uri($this->baseUrl . 'merchants/self/projects/self/')
                ->send();

            if ($response->hasErrors()) {
                throw new \Exception($response->raw_body, $response->code);
            }

            return $this->processProducts($response->body);
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * Process the API response with allowed payment methods.
     *
     * @param array $details
     * @return array
     */
    private function processProducts($details)
    {
        $result = array();

        if (!array_key_exists('permissions', $details)) {
            return $result;
        }

        $products_to_check = array(
            'ideal' => 'ideal',
            'bank-transfer' => 'banktransfer',
            'bancontact' => 'bancontact',
            'cash-on-delivery' => 'cashondelivery',
            'credit-card' => 'creditcard',
            'paypal' => 'paypal',
            'homepay' => 'homepay',
            'klarna' => 'klarna',
            'sofort' => 'sofort',
            'payconiq' => 'payconiq',
            'afterpay' => 'afterpay'
        );

        foreach ($products_to_check as $permission_id => $id) {
            if (array_key_exists('/payment-methods/'.$permission_id.'/', $details['permissions']) &&
                array_key_exists('POST', $details['permissions']['/payment-methods/'.$permission_id.'/']) &&
                $details['permissions']['/payment-methods/'.$permission_id.'/']['POST']
            ) {
                $result[] = $id;
            }
        }

        return $result;
    }

    /**
     * Check if account is in test mode.
     *
     * @return bool
     */
    public function isInTestMode()
    {
        try {
            $response = $this->getHttpClient()
                ->method(Http::GET)
                ->uri($this->baseUrl . 'merchants/self/projects/self/')
                ->send();

            if ($response->hasErrors()) {
                throw new \Exception($response->raw_body, $response->code);
            }

            return $this->isTestMode($response->body);
        } catch (\Exception $exception) {
            throw new ClientException(
                'An error occurred while processing the request: '.$exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * Process test-mode API response.
     *
     * @param array $projectDetails
     * @return bool
     */
    private function isTestMode($projectDetails)
    {
        if (!array_key_exists('status', $projectDetails)) {
            return false;
        }

        return ($projectDetails['status'] == 'active-testing');
    }

    /**
     * Create a new iDEAL order.
     *
     * @param integer $amount Amount in cents.
     * @param string $currency A valid currency code.
     * @param string $issuerId The SWIFT/BIC code of the iDEAL issuer.
     * @param string $description A description of the order.
     * @param string $merchantOrderId A merchant-defined order identifier.
     * @param string $returnUrl The return URL.
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array $customer Customer information.
     * @param array $extra Extra information.
     *
     * @return Order The newly created order.
     */
    public function createIdealOrder(
        $amount,
        $currency,
        $issuerId,
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return $this->postOrder(
            Order::createWithIdeal(
                $amount,
                $currency,
                $issuerId,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new SEPA order.
     *
     * @param integer $amount Amount in cents.
     * @param string $currency A valid currency code.
     * @param array $paymentMethodDetails An array of extra payment method details.
     * @param string $description A description of the order.
     * @param string $merchantOrderId A merchant-defined order identifier.
     * @param string $returnUrl The return URL.
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array $customer Customer information.
     * @param array $extra Extra information.
     *
     * @return Order The newly created order.
     */
    public function createSepaOrder(
        $amount,
        $currency,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return $this->postOrder(
            Order::createWithSepa(
                $amount,
                $currency,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new SOFORT order.
     *
     * @param integer $amount Amount in cents.
     * @param string $currency A valid currency code.
     * @param array $paymentMethodDetails An array of extra payment method details.
     * @param string $description A description of the order.
     * @param string $merchantOrderId A merchant-defined order identifier.
     * @param string $returnUrl The return URL.
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array $customer Customer information.
     * @param array $extra Extra information.
     *
     * @return Order The newly created order.
     */
    public function createSofortOrder(
        $amount,
        $currency,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return $this->postOrder(
            Order::createWithSofort(
                $amount,
                $currency,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }
    
    
    /**
     * Create a new Payconiq order.
     * 
     * @param integer $amount Amount in cents.
     * @param string $currency A valid currency code.
     * @param array $paymentMethodDetails An array of extra payment method details.
     * @param string $description A description of the order.
     * @param string $merchantOrderId A merchant-defined order identifier.
     * @param string $returnUrl The return URL.
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array $customer Customer information.
     * @param array $extra Extra information.
     * @param string $webhookUrl The webhook URL.
     *
     * @return Order The newly created order.
     */
    public function createPayconicOrder(
        $amount,
        $currency,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return $this->postOrder( 
            Order::createWithPayconiq(
                $amount,
                $currency,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new credit card order.
     *
     * @param integer $amount Amount in cents.
     * @param string $currency A valid currency code.
     * @param string $description A description of the order.
     * @param string $merchantOrderId A merchant-defined order identifier.
     * @param string $returnUrl The return URL.
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array $customer Customer information.
     * @param array $extra Extra information.
     *
     * @return Order The newly created order.
     */
    public function createCreditCardOrder(
        $amount,
        $currency,
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return $this->postOrder(
            Order::createWithCreditCard(
                $amount,
                $currency,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new Bancontact order.
     *
     * @param integer $amount Amount in cents.
     * @param string $currency A valid currency code.
     * @param string $description A description of the order.
     * @param string $merchantOrderId A merchant-defined order identifier.
     * @param string $returnUrl The return URL.
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array $customer Customer information.
     * @param array $extra Extra information.
     *
     * @return Order The newly created order.
     */
    public function createBancontactOrder(
        $amount,
        $currency,
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return $this->postOrder(
            Order::createWithBancontact(
                $amount,
                $currency,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new Cash On Delivery order.
     *
     * @param integer $amount Amount in cents.
     * @param string $currency A valid currency code.
     * @param array $paymentMethodDetails An array of extra payment method details.
     * @param string $description A description of the order.
     * @param string $merchantOrderId A merchant-defined order identifier.
     * @param string $returnUrl The return URL.
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array $customer Customer information.
     * @param array $extra Extra information.
     * @param string $webhookUrl The webhook URL.
     *
     * @return Order The newly created order.
     */
    public function createCashOnDeliveryOrder(
        $amount,
        $currency,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return $this->postOrder(
            Order::createWithCod(
                $amount,
                $currency,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new Klarna order.
     *
     * @param integer $amount            Amount in cents.
     * @param string  $currency          A valid currency code.
     * @param string  $description       A description of the order.
     * @param string  $merchantOrderId   A merchant-defined order identifier.
     * @param string  $returnUrl         The return URL.
     * @param string  $expirationPeriod  The expiration period as an ISO 8601 duration
     * @param array   $customer          Customer information.
     * @param array   $extra             Extra information.
     * @param string  $webhookUrl        The webhook URL.
     * @param array   $orderLines        Order lines
     *
     * @return Order The newly created order.
     */
    public function createKlarnaOrder(
        $amount,
        $currency,
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null,
        $orderLines = null
    ) {
        return $this->postOrder(
            Order::createWithKlarna(
                $amount,
                $currency,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl,
                $orderLines
            )
        );
    }

    /**
     * Create a new PayPal order.
     *
     * @param integer $amount Amount in cents.
     * @param string $currency A valid currency code.
     * @param array $paymentMethodDetails An array of extra payment method details.
     * @param string $description A description of the order.
     * @param string $merchantOrderId A merchant-defined order identifier.
     * @param string $returnUrl The return URL.
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array $customer Customer information.
     * @param array $extra Extra information.
     *
     * @return Order The newly created order.
     */
    public function createPaypalOrder(
        $amount,
        $currency,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return $this->postOrder(
            Order::createWithPaypal(
                $amount,
                $currency,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new Home'Pay order.
     *
     * @param integer $amount Amount in cents.
     * @param string $currency A valid currency code.
     * @param array $paymentMethodDetails An array of extra payment method details.
     * @param string $description A description of the order.
     * @param string $merchantOrderId A merchant-defined order identifier.
     * @param string $returnUrl The return URL.
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array $customer Customer information.
     * @param array $extra Extra information.
     *
     * @return Order The newly created order.
     */
    public function createHomepayOrder(
        $amount,
        $currency,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return $this->postOrder(
            Order::createWithHomepay(
                $amount,
                $currency,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new AfterPay order.
     *
     * @param integer $amount            Amount in cents.
     * @param string  $currency          A valid currency code.
     * @param string  $description       A description of the order.
     * @param string  $merchantOrderId   A merchant-defined order identifier.
     * @param string  $returnUrl         The return URL.
     * @param string  $expirationPeriod  The expiration period as an ISO 8601 duration
     * @param array   $customer          Customer information.
     * @param array   $extra             Extra information.
     * @param string  $webhookUrl        The webhook URL.
     * @param array   $orderLines        Order lines
     *
     * @return Order The newly created order.
     */
    public function createAfterPayOrder(
        $amount,
        $currency,
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null,
        $orderLines = null
    ) {
        return $this->postOrder(
            Order::createWithAfterPay(
                $amount,
                $currency,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl,
                $orderLines
            )
        );
    }
    
    /**
     * Create a new order.
     *
     * @param integer $amount Amount in cents.
     * @param string $currency A valid currency code.
     * @param string $paymentMethod The payment method to use.
     * @param array $paymentMethodDetails An array of extra payment method details.
     * @param string $description A description of the order.
     * @param string $merchantOrderId A merchant-defined order identifier.
     * @param string $returnUrl The return URL.
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array $customer Customer information.
     * @param array $extra Extra information.
     * @param string $webhookUrl The webhook URL.
     *
     * @return Order The newly created order.
     */
    public function createOrder(
        $amount,
        $currency,
        $paymentMethod,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return $this->postOrder(
            Order::create(
                $amount,
                $currency,
                $paymentMethod,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Get a single order.
     *
     * @param string $id The order ID.
     * @return Order
     */
    public function getOrder($id)
    {
        try {
            $response = $this->getHttpClient()
                ->method(Http::GET)
                ->uri($this->baseUrl . 'orders/' . $id . '/')
                ->send();

            if ($response->hasErrors()) {
                throw new \Exception($response->raw_body, $response->code);
            }

            return Order::fromArray($response->body);
        } catch (\Exception $exception) {
            if ($exception->getCode() == 404) {
                throw new OrderNotFoundException('No order with that ID was found.', 404, $exception);
            }
            throw new ClientException(
                'An error occurred while getting the order: '.$exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * Update an existing order.
     *
     * @param Order $order
     * @return Order
     */
    public function updateOrder(Order $order)
    {
        return $this->putOrder($order);
    }

    /**
     * Post a new order.
     *
     * @param Order $order
     * @return Order
     */
    private function postOrder(Order $order)
    {
        try {
            $response = $this->getHttpClient()
                ->method(Http::POST)
                ->uri($this->baseUrl . 'orders/')
                ->timeout(30)
                ->sends(Mime::JSON)
                ->body(ArrayFunctions::withoutNullValues($order->toArray()))
                ->send();

            if ($response->hasErrors()) {
                throw new \Exception($response->raw_body, $response->code);
            }

            return Order::fromArray($response->body);
        } catch (\Exception $exception) {
            throw new ClientException(
                'An error occurred while posting the order: '.$exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * PUT order data to Ginger API.
     *
     * @param Order $order
     * @return Order
     */
    private function putOrder(Order $order)
    {
        try {
            $response = $this->getHttpClient()
                ->method(Http::PUT)
                ->uri($this->baseUrl . 'orders/' . $order->id() . '/')
                ->timeout(30)
                ->sends(Mime::JSON)
                ->body(ArrayFunctions::withoutNullValues($order->toArray()))
                ->send();

            if ($response->hasErrors()) {
                throw new \Exception($response->raw_body, $response->code);
            }

            return Order::fromArray($response->body);
        } catch (\Exception $exception) {
            if ($exception->getCode() == 404) {
                throw new OrderNotFoundException('No order with that ID was found.', 404, $exception);
            }
            throw new ClientException(
                'An error occurred while updating the order: '.$exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
    
    /**
     * update the order status to captured
     * 
     * @param  Order $order
     * @throws OrderNotFoundException
     * @throws ClientException
     * @return Transaction
     */
    public function setOrderCapturedStatus(Order $order) 
    {  
        try {
            $transactionId = $order->transactions()->current()->id()->toString();
            $response = $this->getHttpClient()
                ->method(Http::POST)
                ->uri($this->baseUrl . 'orders/' . $order->id() . '/transactions/'. $transactionId . '/captures/')
                ->timeout(30)
                ->sends(Mime::JSON)
                ->send();

            if ($response->hasErrors()) {
                throw new \Exception($response->raw_body, $response->code);
            }

            return Transaction::fromArray($response->body);
        } catch (\Exception $exception) {
            if ($exception->getCode() == 404) {
                throw new OrderNotFoundException('No order with that ID was found.', 404, $exception);
            }
            throw new ClientException(
                'An error occurred while updating the order: '.$exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    private function getHttpClient()
    {
        $this->httpClient->_ch = null;
        return $this->httpClient;
    }
}
