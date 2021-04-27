<?php

namespace GingerPayments\Payment\Order\Transaction\PaymentMethodDetails\CreditCardPaymentMethodDetails;

use GingerPayments\Payment\Common\StringBasedValueObject;
use Assert\Assertion as Guard;

final class VaultToken
{
    use StringBasedValueObject;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        Guard::uuid( $value );

        $this->value = $value;
    }
}
