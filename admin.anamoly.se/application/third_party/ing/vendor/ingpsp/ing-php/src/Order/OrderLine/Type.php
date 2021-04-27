<?php

namespace GingerPayments\Payment\Order\OrderLine;

use GingerPayments\Payment\Common\ChoiceBasedValueObject;

final class Type
{
    use ChoiceBasedValueObject;

    const PHYSICAL = 'physical';
    const DISCOUNT = 'discount';
    const SHIPPING_FEE = 'shipping_fee';

    /**
     * @return array
     */
    public function possibleValues()
    {
        return [
            self::PHYSICAL,
            self::DISCOUNT,
            self::SHIPPING_FEE
        ];
    }

    /**
     * @return bool
     */
    public function isPhysical()
    {
        return $this->value === self::PHYSICAL;
    }

    /**
     * @return bool
     */
    public function isDiscount()
    {
        return $this->value === self::DISCOUNT;
    }

    /**
     * @return bool
     */
    public function isShippingFee()
    {
        return $this->value === self::SHIPPING_FEE;
    }
}
