<?php

namespace MathPHP\Tests\Probability\Distribution\Multivariate;

use MathPHP\Probability\Distribution\Multivariate\Hypergeometric;
use MathPHP\Exception;

class HypergeometricTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         pmf
     * @dataProvider dataProviderForTestHypergeometric
     */
    public function testHypergeometric(array $quantities, array $picks, $expected)
    {
        $dist = new Hypergeometric($quantities);
        $this->assertEquals($expected, $dist->pmf($picks));
    }

    /**
     * @return array
     */
    public function dataProviderForTestHypergeometric()
    {
        return [
            [
                [15, 10, 15],
                [2, 2, 2],
                496125 / 3838380,
            ],
            [
                [5, 10, 15],
                [2, 2, 2],
                47250 / 593775,
            ],
        ];
    }

    /**
     * @test         __construct
     * @dataProvider dataProviderForConstructorExceptions
     */
    public function testConstructorException($quantities)
    {
        $this->expectException(Exception\BadDataException::class);
        $dist = new Hypergeometric($quantities);
    }

    /**
     * @return array
     */
    public function dataProviderForConstructorExceptions()
    {
        return [
            'float' => [
                [1.5, 1, 6],
            ],
            'string' => [
                [10, 'k', 6],
            ],
            'empty' => [
                [],
            ],
        ];
    }

    /**
     * @test         pmf
     * @dataProvider dataProviderForPmfExceptions
     */
    public function testPmfException($ks)
    {
        $this->expectException(Exception\BadDataException::class);
        $dist = new Hypergeometric([10, 10, 10]);
        $prob = $dist->pmf($ks);
    }

    /**
     * @return array
     */
    public function dataProviderForPmfExceptions()
    {
        return [
            'float' => [
                [.5, 1, 6],
            ],
            'string' => [
                [10, 'k', 6],
            ],
            'mismatched' => [
                [-1, 6],
            ],
        ];
    }

    /**
     * @test         pmf, __construct
     * @dataProvider dataProviderForBoundsExceptions
     */
    public function testBoundsExceptions($Ks, $ks)
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        $dist = new Hypergeometric($Ks);
        $prob = $dist->pmf($ks);
    }

    /**
     * @return array
     */
    public function dataProviderForBoundsExceptions()
    {
        return [
            'K too small' => [
                [0, 10, 6],
                [0, 2, 2]
            ],
            'k too small' => [
                [5, 10, 15],
                [-1, 2, 2],
            ],
            'k too big' => [
                [5, 10, 15],
                [6, 2, 2],
            ],
        ];
    }
}
