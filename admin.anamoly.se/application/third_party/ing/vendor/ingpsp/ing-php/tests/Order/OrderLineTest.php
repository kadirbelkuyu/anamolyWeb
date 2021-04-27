<?php

namespace GingerPayments\Payment\Tests\Order;

use GingerPayments\Payment\Order\OrderLine;

final class OrderLineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldCreateFromArray()
    {
        $orderLineData = self::getOrderLinesData();

        $orderLine = OrderLine::fromArray($orderLineData);

        $this->assertInstanceOf(
            'GingerPayments\Payment\Order\OrderLine',
            $orderLine
        );

        $this->assertEquals(
            $orderLine->toArray(),
            $orderLineData
        );

        $this->assertEquals($orderLineData['id'], $orderLine->getId());
        $this->assertEquals($orderLineData['ean'], $orderLine->getEan());
        $this->assertEquals($orderLineData['url'], $orderLine->getUrl());
        $this->assertEquals($orderLineData['name'], $orderLine->getName());
        $this->assertEquals($orderLineData['type'], $orderLine->getType());
        $this->assertEquals($orderLineData['amount'], $orderLine->getAmount());
        $this->assertEquals($orderLineData['currency'], $orderLine->getCurrency());
        $this->assertEquals($orderLineData['quantity'], $orderLine->getQuantity());
        $this->assertEquals($orderLineData['image_url'], $orderLine->getImageUrl());
        $this->assertEquals($orderLineData['discount_rate'], $orderLine->getDiscountRate());
        $this->assertEquals($orderLineData['vat_percentage'], $orderLine->getVatPercentage());
        $this->assertEquals($orderLineData['merchant_order_line_id'], $orderLine->getMerchantOrderLineId());
    }

    /**
     * @test
     */
    public function itShouldGuardRequiredFields()
    {
        $this->setExpectedException('Assert\InvalidArgumentException');
        OrderLine::fromArray([]);
    }

    /**
     * @test
     */
    public function itShouldSetNotRequiredValuesToNull()
    {
        $orderLineData = [
            'name' => "Order Item #1",
            'amount' => 1299,
            'quantity' => 1,
            'currency' => 'EUR',
            'vat_percentage' => 0,
            'merchant_order_line_id' => "AAA001"
        ];

        $orderLine = OrderLine::fromArray($orderLineData);

        $this->assertNull($orderLine->id());
        $this->assertNull($orderLine->ean());
        $this->assertNull($orderLine->url());
        $this->assertNull($orderLine->type());
        $this->assertNull($orderLine->imageUrl());
        $this->assertNull($orderLine->discountRate());
    }

    /**
     * @test
     */
    public function itShouldValidateFields()
    {
        $orderLine = OrderLine::fromArray(self::getOrderLinesData());

        $this->assertTrue($orderLine->currency()->isEUR());
        $this->assertTrue($orderLine->type()->isPhysical());
        $this->assertFalse($orderLine->type()->isDiscount());
        $this->assertFalse($orderLine->type()->isShippingFee());
    }

    public static function getOrderLinesData()
    {
        return [
            'id' => '5ac3eb32-384d-4d61-a797-9f44b1cd70e5',
            'ean' => '9780471117094',
            'url' => 'https://example.com/',
            'name' => 'Order Item Name #1',
            'type' => 'physical',
            'amount' => 1299,
            'currency' => 'EUR',
            'quantity' => 1,
            'image_url' => 'https://example.com/image.jpg',
            'discount_rate' => 0,
            'vat_percentage' => 0,
            'merchant_order_line_id' => 'AAA111'
        ];
    }
}
