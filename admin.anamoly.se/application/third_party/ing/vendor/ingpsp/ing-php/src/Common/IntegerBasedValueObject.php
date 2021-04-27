<?php

namespace GingerPayments\Payment\Common;

trait IntegerBasedValueObject
{
    /**
     * @var integer
     */
    private $value;

    /**
     * Factory method. Returns a new instance from an integer.
     *
     * @param integer $value
     * @return static
     */
    public static function fromInteger($value)
    {
        return new self((int) $value);
    }

    /**
     * @return integer
     */
    public function toInteger()
    {
        return $this->value;
    }

    /**
     * @param integer $value
     */
    private function __construct($value)
    {
        $this->value = $value;
    }
}
