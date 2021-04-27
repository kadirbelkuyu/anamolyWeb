<?php

namespace GingerPayments\Payment\Order\OrderLine;

use Assert\Assertion as Guard;
use GingerPayments\Payment\Common\StringBasedValueObject;

final class Url
{
    use StringBasedValueObject;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        Guard::url($value, 'Item product page URI must be a valid URL.');

        $this->value = $value;
    }
}
