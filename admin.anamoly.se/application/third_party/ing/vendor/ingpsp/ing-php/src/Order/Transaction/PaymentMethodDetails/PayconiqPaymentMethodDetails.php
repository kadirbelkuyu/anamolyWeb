<?php

namespace GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;

use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;

final class PayconiqPaymentMethodDetails implements PaymentMethodDetails
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
     * PayconiqPaymentMethodDetails constructor.
     */
    private function __construct() {}
}
