<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Alcohol\Tests;

use Alcohol\ISO3166;

class ISO3166Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @testdox Calling getByAlpha2 with an invalid alpha2 throws a DomainException.
     * @dataProvider invalidAlpha2Provider
     * @param string $alpha2
     * @expectedException \DomainException
     * @expectedExceptionMessageRegExp /^Not a valid alpha2: .*$/
     */
    public function testGetByAlpha2Invalid($alpha2)
    {
        $iso3166 = new ISO3166();
        $iso3166->getByAlpha2($alpha2);
    }

    /**
     * @testdox Calling getByAlpha2 with an unknown alpha2 throws a OutOfBoundsException.
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage ISO 3166-1 does not contain: ZZ
     */
    public function testGetByAlpha2Unknown()
    {
        $iso3166 = new ISO3166();
        $iso3166->getByAlpha2('ZZ');
    }

    /**
     * @testdox Calling getByAlpha2 with a known alpha2 returns an associative array with the data.
     * @dataProvider alpha2Provider
     * @param string $alpha2
     * @param array $expected
     */
    public function testGetByAlpha2($alpha2, array $expected)
    {
        $iso3166 = new ISO3166();
        $this->assertEquals($expected, $iso3166->getByAlpha2($alpha2));
    }

    /**
     * @testdox Calling getByAlpha3 with an invalid alpha3 throws a DomainException.
     * @param string $alpha3
     * @dataProvider invalidAlpha3Provider
     * @expectedException \DomainException
     * @expectedExceptionMessageRegExp /^Not a valid alpha3: .*$/
     */
    public function testGetByAlpha3Invalid($alpha3)
    {
        $iso3166 = new ISO3166();
        $iso3166->getByAlpha3($alpha3);
    }

    /**
     * @testdox Calling getByAlpha3 with an unknown alpha3 throws a OutOfBoundsException.
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage ISO 3166-1 does not contain: ZZZ
     */
    public function testGetByAlpha3Unknown()
    {
        $iso3166 = new ISO3166();
        $iso3166->getByAlpha3('ZZZ');
    }

    /**
     * @testdox Calling getByAlpha3 with a known alpha3 returns an associative array with the data.
     * @dataProvider alpha3Provider
     * @param string $alpha3
     * @param array $expected
     */
    public function testGetByAlpha3($alpha3, array $expected)
    {
        $iso3166 = new ISO3166();
        $this->assertEquals($expected, $iso3166->getByAlpha3($alpha3));
    }

    /**
     * @testdox Calling getByNumeric with an invalid numeric throws a DomainException.
     * @param string $numeric
     * @dataProvider invalidNumericProvider
     * @expectedException \DomainException
     * @expectedExceptionMessageRegExp /^Not a valid numeric: .*$/
     */
    public function testGetByNumericInvalid($numeric)
    {
        $iso3166 = new ISO3166();
        $iso3166->getByNumeric($numeric);
    }

    /**
     * @testdox Calling getByNumeric with an unknown numeric throws a OutOfBoundsException.
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage ISO 3166-1 does not contain: 000
     */
    public function testGetByNumericUnknown()
    {
        $iso3166 = new ISO3166();
        $iso3166->getByNumeric('000');
    }

    /**
     * @testdox Calling getByNumeric with a known numeric returns an associative array with the data.
     * @dataProvider numericProvider
     * @param string $numeric
     * @param array $expected
     */
    public function testGetByNumeric($numeric, $expected)
    {
        $iso3166 = new ISO3166();
        $this->assertEquals($expected, $iso3166->getByNumeric($numeric));
    }

    /**
     * @testdox Calling getAll returns an array with all elements.
     */
    public function testGetAll()
    {
        $iso3166 = new ISO3166();
        $this->assertInternalType('array', $iso3166->getAll());
        $this->assertCount(249, $iso3166->getAll());
    }

    /**
     * @return array
     */
    public function invalidAlpha2Provider()
    {
        return [['Z'], ['ZZZ'], [1], [123]];
    }

    /**
     * @return array
     */
    public function alpha2Provider()
    {
        return $this->getCountries('alpha2');
    }

    /**
     * @return array
     */
    public function invalidAlpha3Provider()
    {
        return [['ZZ'], ['ZZZZ'], [12], [1234]];
    }

    /**
     * @return array
     */
    public function alpha3Provider()
    {
        return $this->getCountries('alpha3');
    }

    /**
     * @return array
     */
    public function invalidNumericProvider()
    {
        return [['00'], ['0000'], ['ZZ'], ['ZZZZ']];
    }

    /**
     * @return array
     */
    public function numericProvider()
    {
        return $this->getCountries('numeric');
    }

    /**
     * @return array
     */
    private function getCountries($indexedBy)
    {
        $reflected = new \ReflectionClass('Alcohol\ISO3166');
        $countries = $reflected->getProperty('countries');
        $countries->setAccessible(true);
        $countries = $countries->getValue(new ISO3166());

        return array_reduce(
            $countries,
            function (array $carry, array $country) use ($indexedBy) {
                $carry[] = [$country[$indexedBy], $country];
                return $carry;
            },
            []
        );
    }
}
