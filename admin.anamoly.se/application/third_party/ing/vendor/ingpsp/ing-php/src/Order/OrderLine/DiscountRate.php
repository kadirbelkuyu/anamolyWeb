<?php

namespace GingerPayments\Payment\Order\OrderLine;

use GingerPayments\Payment\Common\IntegerBasedValueObject;
use Assert\Assertion as Guard;

final class DiscountRate
{
    use IntegerBasedValueObject;

    /**
     * @param integer $value
     */
    private function __construct($value)
    {
        Guard::min($value, 0, 'Discount rate can not be less than zero.');
        Guard::max($value, 10000, 'Discount rate can not be greater than 10000.');

        $this->value = $value;
    }
}
