<?php

namespace GingerPayments\Payment\Order\OrderLine;

use Assert\Assertion as Guard;
use GingerPayments\Payment\Common\StringBasedValueObject;

final class Ean
{
    use StringBasedValueObject;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        Guard::notBlank($value, 'International Article Number should not be blank.');

        $this->value = $value;
    }
}
