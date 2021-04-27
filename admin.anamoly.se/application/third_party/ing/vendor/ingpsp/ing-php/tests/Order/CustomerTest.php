<?php

namespace GingerPayments\Payment\Tests\Order;

use GingerPayments\Payment\Order\Customer;

final class CustomerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldCreateFromArray()
    {
        $array = [
            'merchant_customer_id' => '123',
            'email_address' => 'email@example.com',
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'address_type' => 'customer',
            'address' => 'Radarweg',
            'postal_code' => '1043 NX',
            'housenumber' => '29 A-12',
            'country' => 'NL',
            'phone_numbers' => [],
            'locale' => null,
            'gender' => 'male',
            'birthdate' => '1988-03-29',
            'ip_address' => '128.0.0.1'
        ];

        $customer = Customer::fromArray($array);

        $this->assertInstanceOf(
            'GingerPayments\Payment\Order\Customer',
            $customer
        );

        $this->assertEquals($array['merchant_customer_id'], (string) $customer->merchantCustomerId());
        $this->assertEquals($array['email_address'], $customer->emailAddress());
        $this->assertEquals($array['first_name'], (string) $customer->firstName());
        $this->assertEquals($array['last_name'], (string) $customer->lastName());
        $this->assertEquals($array['address_type'], (string) $customer->addressType());
        $this->assertEquals($array['address'], (string) $customer->address());
        $this->assertEquals($array['postal_code'], (string) $customer->postalCode());
        $this->assertEquals($array['housenumber'], (string) $customer->housenumber());
        $this->assertEquals($array['country'], (string) $customer->country());
        $this->assertEquals($array['phone_numbers'], $customer->phoneNumbers()->toArray());
        $this->assertEquals($array['gender'], $customer->gender()->toString());
        $this->assertEquals($array['birthdate'], $customer->birthdate()->toString());
        $this->assertEquals($array['ip_address'], $customer->ip()->toString());
    }

    /**
     * @test
     */
    public function itShouldConvertToArray()
    {
        $array = [
            'merchant_customer_id' => '123',
            'email_address' => 'email@example.com',
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'address_type' => 'customer',
            'address' => 'Radarweg',
            'postal_code' => '1043 NX',
            'housenumber' => '29 A-12',
            'country' => 'NL',
            'phone_numbers' => [],
            'locale' => null,
            'gender' => 'male',
            'birthdate' => '1988-03-29',
            'ip_address' => '2001:0db8:85a3:0000:0000:8a2e:0370:7334'
        ];

        $this->assertEquals(
            $array,
            Customer::fromArray($array)->toArray()
        );
    }

    /**
     * @test
     */
    public function itShouldSetMissingValuesToNull()
    {
        $customer = Customer::fromArray([]);

        $this->assertNull($customer->merchantCustomerId());
        $this->assertNull($customer->emailAddress());
        $this->assertNull($customer->firstName());
        $this->assertNull($customer->lastName());
        $this->assertNull($customer->addressType());
        $this->assertNull($customer->address());
        $this->assertNull($customer->postalCode());
        $this->assertNull($customer->housenumber());
        $this->assertNull($customer->country());
        $this->assertNull($customer->phoneNumbers());
        $this->assertNull($customer->gender());
        $this->assertNull($customer->birthdate());
    }
}
