<?php

namespace GingerPayments\Payment\Tests\Common;

use GingerPayments\Payment\Tests\Mock\FakeIntegerBasedValueObject;

final class IntegerBasedValueObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldCreateAnObjectFromInteger()
    {
        $integerValueObject = FakeIntegerBasedValueObject::fromInteger(1);

        $this->assertInstanceOf(
            'GingerPayments\Payment\Tests\Mock\FakeIntegerBasedValueObject',
            $integerValueObject
        );
    }

    /**
     * @test
     */
    public function itShouldConvertToInteger()
    {
        $integerBasedValueObject = FakeIntegerBasedValueObject::fromInteger(12345);

        $this->assertInternalType('integer', $integerBasedValueObject->toInteger());
        $this->assertEquals(12345, $integerBasedValueObject->toInteger());
    }
}
