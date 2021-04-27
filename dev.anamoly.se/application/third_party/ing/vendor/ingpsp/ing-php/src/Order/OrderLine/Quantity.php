<?php

namespace GingerPayments\Payment\Order\OrderLine;

use GingerPayments\Payment\Common\IntegerBasedValueObject;
use Assert\Assertion as Guard;

final class Quantity
{
    use IntegerBasedValueObject;

    /**
     * @param integer $value
     */
    private function __construct($value)
    {
        Guard::min($value, 1, 'Order line quantity must be at least one.');

        $this->value = $value;
    }
}
