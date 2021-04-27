<?php

namespace GingerPayments\Payment\Order\Customer;

use GingerPayments\Payment\Common\ChoiceBasedValueObject;

final class Gender
{
    use ChoiceBasedValueObject;

    const MALE = 'male';
    const FEMALE = 'female';

    /**
     * @return array
     */
    public function possibleValues()
    {
        return [
            self::MALE,
            self::FEMALE
        ];
    }

    /**
     * @return bool
     */
    public function isMale()
    {
        return $this->value === self::MALE;
    }

    /**
     * @return bool
     */
    public function isFemale()
    {
        return $this->value === self::FEMALE;
    }
}
