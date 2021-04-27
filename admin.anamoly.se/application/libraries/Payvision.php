<?php declare(strict_types=1);
include_once APPPATH . 'third_party/payvision/vendor/autoload.php';

use Payvision\SDK\Exception\DataTypeException;
use Payvision\SDK\Infrastructure\ApiConnection;
use PHPUnit\Framework\TestCase;

use Payvision\SDK\Application\Payments\Service\RequestBuilder;
use Payvision\SDK\Domain\Payments\Service\Builder\Composite\Payment\Request as PaymentRequestBuilder;
use Payvision\SDK\Domain\Payments\Service\Builder\Composite\Refund\Request as RefundRequestBuilder;
use Payvision\SDK\Domain\Payments\ValueObject\Payment\Request as PaymentRequest;
use Payvision\SDK\Domain\Payments\ValueObject\Payment\Response as PaymentResponse;
use Payvision\SDK\Exception\Api\ErrorResponse;

class Payvision
{
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
    protected $paymentRequestBuilder;

    /**
     * @var RefundRequestBuilder
     */
    protected $refundRequestBuilder;

    /**
     * @return null
     * @throws DataTypeException
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
        $this->paymentRequestBuilder = new PaymentRequestBuilder();
        $this->refundRequestBuilder = new RefundRequestBuilder();
    }
    protected function generateTrackingCode($prefix): string
    {
        return $prefix."_" . \microtime(true) . '.' . \rand(100000, 999999);
    }
    function get_payment($transactionID){
        //"8f670365-7ff0-401c-84b8-3d6e2413c211"
        $request = RequestBuilder::getTransactionStatus($transactionID,$this->credentials['businessId']);
        /** @var PaymentResponse $response */
        $response = $this->apiConnection->execute($request);
        if($response->getResult() == 0){

            $transaction_code =  $response->getBody()->getTransaction()->getTrackingCode();
            $transaction_amount =  $response->getBody()->getTransaction()->getAmount();
            $transaction_currency =  $response->getBody()->getTransaction()->getCurrencyCode();
            return array("response"=>true,"message"=>$response->getDescription());
        }else{
            return array("response"=>false,"message"=>$response->getDescription());
        }
    }
    function refund($transactionID){

        $request = RequestBuilder::getTransactionStatus($transactionID,$this->credentials['businessId']);
        /** @var PaymentResponse $response */
        $response = $this->apiConnection->execute($request);
        if($response->getResult() == 0){
            $transaction_code =  $response->getBody()->getTransaction()->getTrackingCode();
            $transaction_amount =  $response->getBody()->getTransaction()->getAmount();
            $transaction_currency =  $response->getBody()->getTransaction()->getCurrencyCode();

            $this->refundRequestBuilder->header()->setBusinessId($this->credentials['businessId']);
            $this->refundRequestBuilder->body()->transaction()->setAmount($transaction_amount)
            ->setCurrencyCode($transaction_currency)
            ->setTrackingCode($this->generateTrackingCode("refund"));

            $requestObject = $this->refundRequestBuilder->build();

            $request = RequestBuilder::refundTransaction($requestObject,$transactionID);

            /** @var PaymentResponse $response */
            $response = $this->apiConnection->execute($request);

            if($response->getResult() == PaymentResponse::OK){
                return array("response"=>true,"message"=>$response->getDescription());
            }else{
                return array("response"=>false,"message"=>$response->getDescription());
            }
        }else{
            return array("response"=>false,"message"=>$response->getDescription());
        }


    }
    function paynow($order_id,$amount,$desc=""){
        $this->paymentRequestBuilder->header()->setBusinessId($this->credentials['businessId']);
        $this->paymentRequestBuilder->body()->transaction()
            ->setAmount($amount)
            ->setPurchaseId(strval($order_id))
            ->setInvoiceId(strval($order_id))
            ->setTrackingCode($this->generateTrackingCode($order_id))
            ->setReturnUrl(site_url("idealpayment/response"))
            ->setBrandId(static::BRAND_ID)
            ->setStoreId(static::STORE_ID)
            ->setDescriptor($desc)
            ->setCurrencyCode('EUR');
        $this->paymentRequestBuilder->setAction(PaymentRequest::ACTION_PAYMENT);

        $this->paymentRequestBuilder->body()->bank()
            ->setCountryCode('NL')
            ->setIssuerId(10);

        $requestObject = $this->paymentRequestBuilder->build();

        $request = RequestBuilder::newPayment($requestObject);

        /** @var PaymentResponse $response */
        $response = $this->apiConnection->execute($request);
        if($response->getResult() == PaymentResponse::PENDING){

            $trakingcode =  $response->getBody()->getTransaction()->getTrackingCode();
            $payment_id =  $response->getBody()->getTransaction()->getId();
            $url = $response->getBody()->getRedirect()->getUrl();
            $method = $response->getBody()->getRedirect()->getMethod();
            $fields = $response->getBody()->getRedirect()->getFields();

            return array("response"=>true,"message"=>$response->getDescription(),"url"=>$url,"fields"=>$fields,"method"=>$method,"id"=>$payment_id,"trakingcode"=>$trakingcode);
        }else{
            return array("response"=>false,"message"=>$response->getDescription());
        }
    }
}
