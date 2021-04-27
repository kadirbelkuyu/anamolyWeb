<?php

namespace GingerPayments\Payment\Order\OrderLine;

use GingerPayments\Payment\Common\ChoiceBasedValueObject;

final class Currency
{
    use ChoiceBasedValueObject;

    const EUR = 'EUR';

    /**
     * @return array
     */
    public function possibleValues()
    {
        return [self::EUR];
    }

    /**
     * @return bool
     */
    public function isEUR()
    {
        return $this->value === self::EUR;
    }
}
