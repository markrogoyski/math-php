<?php
namespace MathPHP\Tests\Sequence;

use MathPHP\Sequence\NonInteger;
use MathPHP\Exception;

class NonIntegerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     HarmonicNumber produces the expected sequence
     * @dataProvider dataProviderForHarmonicNumbers
     */
    public function testHarmonicNumbers(int $n, int $d, int $a₁, array $progression)
    {
        $this->assertEquals($progression, NonInteger::Harmonic($n));
    }
    public function dataProviderForHarmonicNumbers(): array
    {
        return [
            [10, [1 => 1, 3/2, 11/6, 25/12, 137/60, 49/20, 363/140, 761/280, 7129/2520, 7381/2520]],
        ];
    }
}
