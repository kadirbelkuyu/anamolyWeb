<?php

namespace GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;

use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;

final class KlarnaPaymentMethodDetails implements PaymentMethodDetails
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
     * KlarnaPaymentMethodDetails constructor.
     */
    private function __construct()
    {
    }
}
