<?php

namespace GingerPayments\Payment\Order\Transaction\PaymentMethodDetails\CreditCardPaymentMethodDetails;

use GingerPayments\Payment\Common\ChoiceBasedValueObject;

final class LiabilityShift
{
    use ChoiceBasedValueObject;

    public function possibleValues()
    {
        return [
          true,
          false,
        ];
    }

    public static function fromBoolean($value)
    {
        return new self((bool)$value);
    }
}
