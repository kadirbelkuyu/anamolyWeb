<?php

namespace GingerPayments\Payment\Order\Customer;

use Assert\Assertion as Guard;
use GingerPayments\Payment\Common\StringBasedValueObject;

final class Birthdate
{
    use StringBasedValueObject;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        Guard::date($value, 'Y-m-d');

        $this->value = $value;
    }
}
