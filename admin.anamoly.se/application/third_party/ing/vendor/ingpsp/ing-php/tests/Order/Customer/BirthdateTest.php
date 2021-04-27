<?php

namespace GingerPayments\Payment\Tests\Order\Customer;

use GingerPayments\Payment\Order\Customer\Birthdate;

final class BirthdateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldInstantiateFromAValidString()
    {
        $this->assertInstanceOf(
            'GingerPayments\Payment\Order\Customer\Birthdate',
            Birthdate::fromString('1988-03-29')
        );
    }

    /**
     * @test
     */
    public function itCanNotBeEmptyString()
    {
        $this->setExpectedException('Assert\InvalidArgumentException');

        $this->assertEmpty(Birthdate::fromString('')->toString());
    }

    /**
     * @test
     */
    public function formatShouldBeCorrect()
    {
        $this->setExpectedException('Assert\InvalidArgumentException');

        Birthdate::fromString('29-03-1998');
    }

    /**
     * @test
     */
    public function itShouldReturnValidDatetime()
    {
        $this->assertEquals(Birthdate::fromString('1988-03-29')->toString(), '1988-03-29');
    }
}
