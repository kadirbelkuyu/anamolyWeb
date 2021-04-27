<?php

namespace GingerPayments\Payment\Order\OrderLine;

use Assert\Assertion as Guard;
use GingerPayments\Payment\Common\IntegerBasedValueObject;

final class Amount
{
    use IntegerBasedValueObject;

    /**
     * @param integer $value
     */
    private function __construct($value)
    {
        Guard::integer($value, 'Order line amount must be a valid integer.');

        $this->value = $value;
    }
}
