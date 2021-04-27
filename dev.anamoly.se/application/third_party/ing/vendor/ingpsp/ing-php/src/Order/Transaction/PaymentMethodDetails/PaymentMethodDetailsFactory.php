<?php

namespace GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;

use GingerPayments\Payment\Order\Transaction\PaymentMethod;

final class PaymentMethodDetailsFactory
{
    public static function createFromArray(PaymentMethod $paymentMethod, array $paymentMethodDetails)
    {
        if ($paymentMethod->isIdeal()) {
            return IdealPaymentMethodDetails::fromArray($paymentMethodDetails);
        }

        if ($paymentMethod->isCreditCard()) {
            return CreditCardPaymentMethodDetails::fromArray($paymentMethodDetails);
        }

        if ($paymentMethod->isBankTransfer()) {
            return SepaPaymentMethodDetails::fromArray($paymentMethodDetails);
        }

        if ($paymentMethod->isSofort()) {
            return SofortPaymentMethodDetails::fromArray($paymentMethodDetails);
        }

        if ($paymentMethod->isBancontact()) {
            return BancontactPaymentMethodDetails::fromArray($paymentMethodDetails);
        }

        if ($paymentMethod->isCashOnDelivery()) {
            return CashOnDeliveryPaymentMethodDetails::fromArray($paymentMethodDetails);
        }

        if ($paymentMethod->isKlarna()) {
            return KlarnaPaymentMethodDetails::fromArray($paymentMethodDetails);
        }

        if ($paymentMethod->isPayPal()) {
            return PayPalPaymentMethodDetails::fromArray($paymentMethodDetails);
        }

        if ($paymentMethod->isHomePay()) {
            return HomePayPaymentMethodDetails::fromArray($paymentMethodDetails);
        }
        
        if ($paymentMethod->isPayconiq()) { 
            return PayconiqPaymentMethodDetails::fromArray($paymentMethodDetails);
        }

        if ($paymentMethod->isAfterPay()) { 
            return AfterPayPaymentMethodDetails::fromArray($paymentMethodDetails);
        }

        throw new \InvalidArgumentException('Provided payment method not supported.');
    }
}
