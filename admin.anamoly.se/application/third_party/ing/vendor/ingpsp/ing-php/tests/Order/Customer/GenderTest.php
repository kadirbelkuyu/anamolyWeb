<?php

namespace GingerPayments\Payment\Tests\Order\Customer;

use GingerPayments\Payment\Order\Customer\Gender;

final class StatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldInstantiateFromAValidGender()
    {
        $this->assertInstanceOf(
            'GingerPayments\Payment\Order\Customer\Gender',
            Gender::fromString(Gender::MALE)
        );
    }

    /**
     * @test
     */
    public function itCanBeMale()
    {
        $gender = Gender::fromString(Gender::MALE);

        $this->assertTrue($gender->isMale());
        $this->assertFalse($gender->isFemale());
    }

    /**
     * @test
     */
    public function itCanBeFemale()
    {
        $gender = Gender::fromString(Gender::FEMALE);

        $this->assertFalse($gender->isMale());
        $this->assertTrue($gender->isFemale());
    }

    /**
     * @test
     */
    public function itShouldFailOnInvalidGender()
    {
        $this->setExpectedException('Assert\InvalidArgumentException');
        Gender::fromString('androgynous');
    }
}
