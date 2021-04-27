<?php

namespace GingerPayments\Payment\Order\Transaction;

use GingerPayments\Payment\Common\ChoiceBasedValueObject;

final class PaymentMethod
{
    use ChoiceBasedValueObject;

    /**
     * Possible payment methods
     */
    const IDEAL = 'ideal';
    const CREDIT_CARD = 'credit-card';
    const BANK_TRANSFER = 'bank-transfer';
    const SOFORT = 'sofort';
    const BANCONTACT = 'bancontact';
    const COD = 'cash-on-delivery';
    const KLARNA = 'klarna';
    const PAYPAL = 'paypal';
    const HOMEPAY = 'homepay';
    const PAYCONIQ = 'payconiq';
    const AFTERPAY = 'afterpay';

    /**
     * @return array
     */
    public function possibleValues()
    {
        return [
            self::IDEAL,
            self::CREDIT_CARD,
            self::BANK_TRANSFER,
            self::SOFORT,
            self::BANCONTACT,
            self::COD,
            self::KLARNA,
            self::PAYPAL,
            self::HOMEPAY,
            self::PAYCONIQ,
            self::AFTERPAY
        ];
    }

    /**
     * @return bool
     */
    public function isIdeal()
    {
        return $this->value === self::IDEAL;
    }

    /**
     * @return bool
     */
    public function isCreditCard()
    {
        return $this->value === self::CREDIT_CARD;
    }

    /**
     * @return bool
     */
    public function isBankTransfer()
    {
        return $this->value === self::BANK_TRANSFER;
    }

    /**
     * @return bool
     */
    public function isSofort()
    {
        return $this->value === self::SOFORT;
    }

    /**
     * @return bool
     */
    public function isBancontact()
    {
        return $this->value === self::BANCONTACT;
    }

    /**
     * @return bool
     */
    public function isCashOnDelivery()
    {
        return $this->value === self::COD;
    }

    /**
     * @return bool
     */
    public function isKlarna()
    {
        return $this->value === self::KLARNA;
    }

    /**
     * @return bool
     */
    public function isPayPal()
    {
        return $this->value === self::PAYPAL;
    }

    /**
     * @return bool
     */
    public function isHomePay()
    {
        return $this->value === self::HOMEPAY;
    }
    
    /**
     * @return bool
     */
    public function isPayconiq()
    {
        return $this->value === self::PAYCONIQ;
    }
    
    /**
     * @return bool
     */
    public function isAfterPay()
    {
        return $this->value === self::AFTERPAY;
    }
}
