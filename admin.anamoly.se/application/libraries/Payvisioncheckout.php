<?php declare(strict_types=1);
include_once APPPATH . 'third_party/payvision/vendor/autoload.php';


use Payvision\SDK\Exception\DataTypeException;
use Payvision\SDK\Infrastructure\ApiConnection;
use Payvision\SDK\Application\Checkouts\Service\RequestBuilder;
use Payvision\SDK\Domain\Checkouts\ValueObject\Checkout\Request as CheckoutRequestObject;
use Payvision\SDK\Domain\Checkouts\ValueObject\Checkout\RequestBody as CheckoutRequestBody;
use Payvision\SDK\Domain\Checkouts\ValueObject\Checkout\RequestCheckout as CheckoutRequestCheckout;
use Payvision\SDK\Domain\Checkouts\ValueObject\Checkout\RequestTransaction as CheckoutRequestTransaction;
use Payvision\SDK\Domain\Checkouts\ValueObject\Checkout\Response as CheckoutResponse;
use Payvision\SDK\Domain\Checkouts\ValueObject\Request\Header;
use Payvision\SDK\Domain\Checkouts\ValueObject\Status\Response as StatusResponse;
use Payvision\SDK\Exception\Api\ErrorResponse;
use Payvision\SDK\Exception\ApiException;
use Payvision\SDK\Exception\BuilderException;

class Payvisioncheckout{
    /**
     * @var ApiConnection
     */
    protected $apiConnection;

    /**
     * @var array
     */
    protected $credentials;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @throws DataTypeException
     * @return null
     */
    const BRAND_ID = 3010;
    const STORE_ID = 1;
    /**
     * @var PaymentRequestBuilder
     */

    public function __construct()
    {
		/*
        $this->credentials = [
            'username' => '',
            'password' => '',
            'businessId' => '',
        ];
		*/

        $this->credentials = [
            'username' => '',
            'password' => '',
            'businessId' => '',
        ];

        $this->apiConnection = new ApiConnection(
            $this->credentials['username'],
            $this->credentials['password'],
            ApiConnection::URI_LIVE,
            $this->debug
        );
        $this->credentials['username'];

    }
    function checkout($amount){
        $order_id = "P".time();
        $requestObject = new CheckoutRequestObject(
            new CheckoutRequestBody(
                new CheckoutRequestCheckout(
                    ['3010'],
                    site_url("idealpayment/response")
                ),
                new CheckoutRequestTransaction(
                    floatval($amount),
                    CheckoutRequestTransaction::AUTHORIZATION_MODE_PAYMENT,
                    'EUR',
                    $this->generateTrackingCode("checkout"),null,null,null,null,null,$order_id,null,$this::STORE_ID,null
                )
            ),
            new Header($this->credentials['businessId'])
        );

        $apiRequest = RequestBuilder::newCheckout($requestObject);

        /** @var CheckoutResponse $response */
        $response = $this->apiConnection->execute($apiRequest);


        return [
            'checkoutId' => $response->getBody()->getCheckout()->getCheckoutId(),
        ];
    }
    function checkOutStatusIsPending($checkoutid){
        $apiRequest = RequestBuilder::getCheckoutStatus($checkoutid, $this->credentials['businessId']);
        /** @var StatusResponse $response */
        $response = $this->apiConnection->execute($apiRequest);
        if ($response->getResult() == StatusResponse::PENDING) {
            return true;
        }else{
            return false;
        }
    }
    protected function generateTrackingCode($prefix): string
    {
        return $prefix."_" . \microtime(true) . '.' . \rand(100000, 999999);
    }
}
