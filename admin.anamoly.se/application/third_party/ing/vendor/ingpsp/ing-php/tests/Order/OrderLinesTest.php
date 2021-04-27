<?php

namespace GingerPayments\Payment\Tests\Order;

use GingerPayments\Payment\Order\OrderLines;

final class OrderLinesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldCreate()
    {
        $this->assertInstanceOf(
            'GingerPayments\Payment\Order\OrderLines',
            OrderLines::create()
        );
    }

    /**
     * @test
     */
    public function itShouldCreateFromArray()
    {
        $this->assertInstanceOf(
            'GingerPayments\Payment\Order\OrderLines',
            OrderLines::fromArray([])
        );
    }

    /**
     * @test
     */
    public function itShouldBeTraversable()
    {
        $iterations = 0;
        foreach (OrderLines::fromArray(self::getOrderLinesData()) as $key => $orderLine) {
            $this->assertEquals($iterations, $key);
            $this->assertInstanceOf('GingerPayments\Payment\Order\OrderLine', $orderLine);
            $iterations++;
        }
        $this->assertEquals(2, $iterations);
    }

    /**
     * @test
     */
    public function itShouldConvertToArray()
    {
        $array = self::getOrderLinesData();

        $this->assertEquals(
            $array,
            OrderLines::fromArray($array)->toArray()
        );
    }

    public static function getOrderLinesData()
    {
        return [
            [
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
                'merchant_order_line_id' => 'AAA222'
            ],
            [
                'id' => '5ac3eb32-384d-4d61-a797-9f44b1cd70e5',
                'ean' => '9780471117094',
                'url' => 'https://example.com/',
                'name' => 'Order Item Name #1',
                'type' => 'physical',
                'amount' => 1299,
                'currency' => 'EUR',
                'quantity' => 10,
                'image_url' => 'https://example.com/image.jpg',
                'discount_rate' => 1000,
                'vat_percentage' => 2000,
                'merchant_order_line_id' => 'AAA222'
            ]
        ];
    }
}
