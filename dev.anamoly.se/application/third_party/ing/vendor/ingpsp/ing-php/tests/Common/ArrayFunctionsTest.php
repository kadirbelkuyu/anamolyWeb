<?php

namespace GingerPayments\Payment\Tests\Common;

use GingerPayments\Payment\Common\ArrayFunctions;

final class ArrayFunctionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldRemoveNullValues()
    {
        $array = [
            0 => 'foo',
            1 => 'bar',
            2 => null,
            3 => [
                0 => 'foo',
                1 => null,
                2 => 'bar',
                3 => []
            ],
            4 => null,
            5 => [],
            6 => ['array' => []],
            7 => [[]],
            'foo' => null,
            'baz' => [],
            'bar' => 0,
            'bar_string' => '0',
            [],
            'empty_string' => "",
            'empty_array' => [""]
        ];

        $expected = [
            0 => 'foo',
            1 => 'bar',
            3 => [
                0 => 'foo',
                2 => 'bar'
            ],
            'bar' => 0,
            'bar_string' => '0'
        ];

        $this->assertEquals($expected, ArrayFunctions::withoutNullValues($array));
    }
}
