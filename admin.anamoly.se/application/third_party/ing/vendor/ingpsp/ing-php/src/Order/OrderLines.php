<?php

namespace GingerPayments\Payment\Order;

use Assert\Assertion as Guard;

final class OrderLines implements \Iterator
{
    /**
     * @var OrderLine[]
     */
    private $orderLines;

    /**
     * @return OrderLines
     */
    public static function create()
    {
        return new static([]);
    }

    /**
     * @param array $orderLines
     * @return OrderLines
     */
    public static function fromArray(array $orderLines)
    {
        return new static(
            array_map(
                function ($orderLine) {
                    return OrderLine::fromArray($orderLine);
                },
                $orderLines
            )
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_map(
            function (OrderLine $orderLine) {
                return $orderLine->toArray();
            },
            $this->orderLines
        );
    }

    /**
     * @return OrderLine|mixed
     */
    public function current()
    {
        return current($this->orderLines);
    }

    /**
     * @return OrderLine|mixed
     */
    public function next()
    {
        return next($this->orderLines);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->orderLines);
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        $key = key($this->orderLines);
        return ($key !== null && $key !== false);
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        reset($this->orderLines);
    }

    /**
     * @param OrderLine[] $orderLines
     */
    private function __construct(array $orderLines = [])
    {
        Guard::allIsInstanceOf($orderLines, 'GingerPayments\Payment\Order\OrderLine');

        $this->orderLines = $orderLines;
    }
}
