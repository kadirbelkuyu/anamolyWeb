<?php

namespace GingerPayments\Payment\Order\Customer;

use GingerPayments\Payment\Common\StringBasedValueObject;
use Assert\Assertion as Guard;

final class IP
{
    use StringBasedValueObject;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        Guard::ip($value, "Customer IP must me a valid IP address.");

        $this->value = $value;
    }
}
