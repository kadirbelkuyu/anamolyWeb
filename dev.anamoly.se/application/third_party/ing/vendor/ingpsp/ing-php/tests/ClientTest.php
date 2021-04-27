<?php

namespace GingerPayments\Payment\Tests;

use GingerPayments\Payment\Client;
use GingerPayments\Payment\Common\ArrayFunctions;
use GingerPayments\Payment\Order;
use Mockery as m;

final class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    private $httpResponse;

    /**
     * @var \Mockery\MockInterface
     */
    private $httpClient;

    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->httpResponse = m::mock('Httpful\Response')->makePartial();
        $this->httpClient = m::mock('Httpful\Request')->makePartial();
        $this->client = new Client('http://www.example.com/', $this->httpClient);
    }

    /**
     * @test
     */
    public function itShouldVerifySSLUsingBundledCA()
    {
        $this->httpClient->shouldReceive('addOnCurlOption')
            ->once()
            ->with(CURLOPT_CAINFO, realpath(dirname(__FILE__).'/../assets/cacert.pem'))
            ->andReturn($this->httpClient);

        $this->client->useBundledCA();
    }

    /**
     * @test
     */
    public function itShouldGetIdealIssuers()
    {
        $this->httpResponse->body = [];
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $response = $this->client->getIdealIssuers();

        $this->assertEquals('GET', $this->httpClient->method);
        $this->assertStringEndsWith('/ideal/issuers/', $this->httpClient->uri);
        $this->assertInstanceOf('GingerPayments\Payment\Ideal\Issuers', $response);
    }

    /**
     * @test
     */
    public function itShouldCatchExceptionsWhenGettingIdealIssuers()
    {
        $this->httpClient->shouldReceive('send')
            ->andThrow(new \Exception('Something happened', 456));

        $this->setExpectedException('GingerPayments\Payment\Client\ClientException');
        $this->client->getIdealIssuers();
    }

    /**
     * @test
     */
    public function itShouldCreateAnOrder()
    {
        $order = Order::create(
            1234,
            'EUR',
            'credit-card',
            [],
            'A nice description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->httpResponse->body = ArrayFunctions::withoutNullValues($order->toArray());
        $this->httpClient->shouldReceive('body')
            ->with(ArrayFunctions::withoutNullValues($order->toArray()))
            ->andReturn($this->httpClient);
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $response = $this->client->createOrder(
            1234,
            'EUR',
            'credit-card',
            [],
            'A nice description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->assertEquals('POST', $this->httpClient->method);
        $this->assertStringEndsWith('/orders/', $this->httpClient->uri);
        $this->assertInstanceOf('GingerPayments\Payment\Order', $response);
        $this->assertEquals($response, $order);
    }

    /**
     * @test
     */
    public function itShouldCreateACreditCardOrder()
    {
        $order = Order::create(
            1234,
            'EUR',
            'credit-card',
            [],
            'A nice description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->httpResponse->body = ArrayFunctions::withoutNullValues($order->toArray());
        $this->httpClient->shouldReceive('body')
            ->with(ArrayFunctions::withoutNullValues($order->toArray()))
            ->andReturn($this->httpClient);
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $response = $this->client->createCreditCardOrder(
            1234,
            'EUR',
            'A nice description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->assertEquals('POST', $this->httpClient->method);
        $this->assertStringEndsWith('/orders/', $this->httpClient->uri);
        $this->assertInstanceOf('GingerPayments\Payment\Order', $response);
        $this->assertEquals($response, $order);
    }

    /**
     * @test
     */
    public function itShouldCreateAnIdealOrder()
    {
        $order = Order::create(
            1234,
            'EUR',
            'ideal',
            ['issuer_id' => 'ABNANL2A'],
            'A nice description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->httpResponse->body = ArrayFunctions::withoutNullValues($order->toArray());
        $this->httpClient->shouldReceive('body')
            ->with(ArrayFunctions::withoutNullValues($order->toArray()))
            ->andReturn($this->httpClient);
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $response = $this->client->createIdealOrder(
            1234,
            'EUR',
            'ABNANL2A',
            'A nice description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->assertEquals('POST', $this->httpClient->method);
        $this->assertStringEndsWith('/orders/', $this->httpClient->uri);
        $this->assertInstanceOf('GingerPayments\Payment\Order', $response);
        $this->assertEquals($response, $order);
    }

    /**
     * @test
     */
    public function itShouldCreateABankTransferOrder()
    {
        $order = Order::create(
            1234,
            'EUR',
            'bank-transfer',
            [],
            'Bank Transfer order description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->httpResponse->body = ArrayFunctions::withoutNullValues($order->toArray());
        $this->httpClient->shouldReceive('body')
            ->with(ArrayFunctions::withoutNullValues($order->toArray()))
            ->andReturn($this->httpClient);
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $response = $this->client->createSepaOrder(
            1234,
            'EUR',
            [],
            'Bank Transfer order description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->assertEquals('POST', $this->httpClient->method);
        $this->assertStringEndsWith('/orders/', $this->httpClient->uri);
        $this->assertInstanceOf('GingerPayments\Payment\Order', $response);
        $this->assertEquals($response, $order);
    }

    /**
     * @test
     */
    public function itShouldCreateABancontactOrder()
    {
        $order = Order::create(
            1234,
            'EUR',
            'bancontact',
            [],
            'Bancontact order description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->httpResponse->body = ArrayFunctions::withoutNullValues($order->toArray());
        $this->httpClient->shouldReceive('body')
            ->with(ArrayFunctions::withoutNullValues($order->toArray()))
            ->andReturn($this->httpClient);
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $response = $this->client->createBancontactOrder(
            1234,
            'EUR',
            'Bancontact order description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->assertEquals('POST', $this->httpClient->method);
        $this->assertStringEndsWith('/orders/', $this->httpClient->uri);
        $this->assertInstanceOf('GingerPayments\Payment\Order', $response);
        $this->assertEquals($response, $order);
    }

    /**
     * @test
     */
    public function itShouldCreateASofortOrder()
    {
        $order = Order::create(
            1234,
            'EUR',
            'sofort',
            [],
            'Sofort Transfer order description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->httpResponse->body = ArrayFunctions::withoutNullValues($order->toArray());
        $this->httpClient->shouldReceive('body')
            ->with(ArrayFunctions::withoutNullValues($order->toArray()))
            ->andReturn($this->httpClient);
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $response = $this->client->createSofortOrder(
            1234,
            'EUR',
            [],
            'Sofort Transfer order description',
            'my-order-id',
            'http://www.example.com',
            'PT10M'
        );

        $this->assertEquals('POST', $this->httpClient->method);
        $this->assertStringEndsWith('/orders/', $this->httpClient->uri);
        $this->assertInstanceOf('GingerPayments\Payment\Order', $response);
        $this->assertEquals($response, $order);
    }

    /**
     * @test
     */
    public function itShouldUpdateOrder()
    {
        $orderData = [
            'transactions' => [['payment_method' => 'credit-card']],
            'amount' => 9999,
            'currency' => 'EUR',
            'id' => 'c384b47e-7a5e-4c91-ab65-c4eed7f26e85',
            'expiration_period' => 'PT10M',
            'merchant_order_id' => '123',
            'description' => "Test",
            'return_url' => "http://example.com",
            'webhook_url' => "http://example.com/webhook"
        ];

        $order = Order::fromArray($orderData);

        $this->httpResponse->body = ArrayFunctions::withoutNullValues($order->toArray());
        $this->httpClient->shouldReceive('body')
            ->with(ArrayFunctions::withoutNullValues($order->toArray()))
            ->andReturn($this->httpClient);
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $response = $this->client->updateOrder($order);

        $this->assertEquals('PUT', $this->httpClient->method);
        $this->assertStringEndsWith('/orders/c384b47e-7a5e-4c91-ab65-c4eed7f26e85/', $this->httpClient->uri);
        $this->assertInstanceOf('GingerPayments\Payment\Order', $response);
        $this->assertEquals($response, $order);
    }

    /**
     * @test
     */
    public function itShouldThrowAnOrderNotFoundExceptionWhenUpdatingOrder()
    {
        $orderData = [
            'transactions' => [],
            'amount' => 9999,
            'currency' => 'EUR',
        ];

        $this->httpResponse->body = 'Something happened';
        $this->httpResponse->code = 404;
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $this->setExpectedException('GingerPayments\Payment\Client\OrderNotFoundException');
        $this->client->updateOrder(Order::fromArray($orderData));
    }

    /**
     * @test
     */
    public function itShouldThrowAClientExceptionWhenUpdatingOrder()
    {
        $orderData = [
            'transactions' => [],
            'amount' => 9999,
            'currency' => 'EUR',
        ];

        $this->httpResponse->body = 'Something happened';
        $this->httpResponse->code = 500;
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $this->setExpectedException('GingerPayments\Payment\Client\ClientException');
        $this->client->updateOrder(Order::fromArray($orderData));
    }

    /**
     * @test
     */
    public function itShouldCatchExceptionsWhenCreatingAnOrder()
    {
        $this->httpResponse->body = 'Something happened';
        $this->httpResponse->code = 500;
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $this->setExpectedException('GingerPayments\Payment\Client\ClientException');
        $this->client->createOrder(1234, 'EUR', 'credit-card');
    }

    /**
     * @test
     */
    public function itShouldGetAnOrder()
    {
        $this->httpResponse->body = [
            'amount' => 1234,
            'currency' => 'EUR',
            'transactions' => [
                ['payment_method' => 'credit-card']
            ]
        ];
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $response = $this->client->getOrder('123456');

        $this->assertEquals('GET', $this->httpClient->method);
        $this->assertStringEndsWith('/orders/123456/', $this->httpClient->uri);
        $this->assertInstanceOf('GingerPayments\Payment\Order', $response);
    }

    /**
     * @test
     */
    public function itShouldThrowAnOrderNotFoundExceptionWhenGettingAnOrder()
    {
        $this->httpResponse->body = 'Something happened';
        $this->httpResponse->code = 404;
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $this->setExpectedException('GingerPayments\Payment\Client\OrderNotFoundException');
        $this->client->getOrder('123456');
    }

    /**
     * @test
     */
    public function itShouldThrowAClientExceptionWhenGettingAnOrder()
    {
        $this->httpResponse->body = 'Something happened';
        $this->httpResponse->code = 500;
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $this->setExpectedException('GingerPayments\Payment\Client\ClientException');
        $this->client->getOrder('123456');
    }
    
    /**
     * @test
     */
    public function itShouldSetOrderStatusToCaptured() 
    {
        $this->httpResponse->body = [
            'payment_method' => 'credit-card',
            'payment_method_details' => [],
            'id' => '5ac3eb32-384d-4d61-a797-9f44b1cd70e5',
            'created' => '2015-03-07T20:58:35+0100',
            'modified' => '2015-03-07T21:58:35+0100',
            'completed' => '2015-03-07T22:58:35+0100',
            'status' => 'new',
            'reason' => 'A great reason',
            'currency' => 'EUR',
            'amount' => 100,
            'expiration_period' => 'P0Y0M0DT1H0M0S',
            'description' => 'A transaction',
            'balance' => 'internal',
            'payment_url' => 'http://www.example.com'
        ];
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $response = $this->client->setOrderCapturedStatus($this->createOrderWithTransactionsFromArray());
        
        $this->assertEquals('POST', $this->httpClient->method);
        $this->assertStringEndsWith(
            '/orders/c384b47e-7a5e-4c91-ab65-c4eed7f26e85/transactions/5ac3eb32-384d-4d61-a797-9f44b1cd70e5/captures/',
            $this->httpClient->uri
        );
        $this->assertInstanceOf('GingerPayments\Payment\Order\Transaction', $response);
         
    }
    
    /**
     * @test
     */
    public function itShouldThrowAnOrderNotFoundExceptionWhenSettingCapturedOrderStatus()
    {
        $this->httpResponse->body = 'Something happened';
        $this->httpResponse->code = 404;
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $this->setExpectedException('GingerPayments\Payment\Client\OrderNotFoundException');
        $this->client->setOrderCapturedStatus($this->createOrderWithTransactionsFromArray());
    }
    
    /**
     * @test
     */
    public function itShouldThrowAClientExceptionWhenSettingCapturedOrderStatus()
    {
        $this->httpResponse->body = 'Something happened';
        $this->httpResponse->code = 500;
        $this->httpClient->shouldReceive('send')
            ->andReturn($this->httpResponse);

        $this->setExpectedException('GingerPayments\Payment\Client\ClientException');
        $this->client->setOrderCapturedStatus($this->createOrderWithTransactionsFromArray());
    }
    
    /**
     * Helper method for creating an order with transactions
     * 
     * @return Order
     */
    protected function createOrderWithTransactionsFromArray() {
        $orderData = [
            'transactions' => [
                 [
                    'payment_method' => 'credit-card',
                    'payment_method_details' => [],
                    'id' => '5ac3eb32-384d-4d61-a797-9f44b1cd70e5',
                    'created' => '2015-03-07T20:58:35+0100',
                    'modified' => '2015-03-07T21:58:35+0100',
                    'completed' => '2015-03-07T22:58:35+0100',
                    'status' => 'new',
                    'reason' => 'A great reason',
                    'currency' => 'EUR',
                    'amount' => 100,
                    'expiration_period' => 'P0Y0M0DT1H0M0S',
                    'description' => 'A transaction',
                    'balance' => 'internal',
                    'payment_url' => 'http://www.example.com'
                ]
            ],
            'amount' => 100,
            'currency' => 'EUR',
            'id' => 'c384b47e-7a5e-4c91-ab65-c4eed7f26e85',
            'expiration_period' => 'PT10M',
            'merchant_order_id' => '123',
            'description' => "Test",
            'return_url' => "http://example.com",
        ];
        return Order::fromArray($orderData);
    }
}
