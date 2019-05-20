<?php
namespace MathPHP\Tests\Sequence;

use MathPHP\Sequence\NonInteger;

class NonIntegerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         HarmonicNumber produces the expected sequence
     * @dataProvider dataProviderForHarmonicNumbers
     * @param int   $n
     * @param array $expectedSequence
     */
    public function testHarmonicNumbers(int $n, array $expectedSequence)
    {
        // When
        $harmonicSequence = NonInteger::Harmonic($n);

        // Then
        $this->assertEquals($expectedSequence, $harmonicSequence);
    }

    /**
     * @return array
     */
    public function dataProviderForHarmonicNumbers(): array
    {
        return [
            [-1, []],
            [0, []],
            [1, [1 => 1]],
            [10, [1 => 1, 3/2, 11/6, 25/12, 137/60, 49/20, 363/140, 761/280, 7129/2520, 7381/2520]],
        ];
    }

    /**
     * @test         Hyperharmonic produces the expected sequence
     * @dataProvider dataProviderForHyperharmonicNumbers
     * @param int   $n
     * @param float $p
     * @param array $expectedSequence
     */
    public function testHyperharmonicNumbers(int $n, float $p, array $expectedSequence)
    {
        // When
        $harmonicSequence = NonInteger::Hyperharmonic($n, $p);

        // Then
        $this->assertEquals($expectedSequence, $harmonicSequence);
    }

    /**
     * @return array
     */
    public function dataProviderForHyperharmonicNumbers(): array
    {
        return [
            [-1, 2, []],
            [0, 2, []],
            [1, 2, [1 => 1]],
            [4, 2, [1 => 1, 5/4, 49/36, 205/144]],
            [3, 1.01, [1 => 1, 1.4965462477, 1.8262375824425]],
        ];
    }
}
