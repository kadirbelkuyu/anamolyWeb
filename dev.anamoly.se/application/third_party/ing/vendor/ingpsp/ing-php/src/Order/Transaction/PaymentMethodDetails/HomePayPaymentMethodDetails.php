<?php

namespace GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;

use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;

final class HomePayPaymentMethodDetails implements PaymentMethodDetails
{
    /**
     * @param array $details
     * @return static
     */
    public static function fromArray(array $details)
    {
        return new static();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [];
    }

    /**
     * HomePayPaymentMethodDetails constructor.
     */
    private function __construct() {}
}
