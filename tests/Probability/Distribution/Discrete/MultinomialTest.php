<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Multinomial;
use MathPHP\Exception;

class MultinomialTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pmf
     * @dataProvider dataProviderForPMF
     * @param        array $frequencies
     * @param        array $probabilities
     */
    public function testPMF(array $frequencies, array $probabilities, $pmf)
    {
        $multinomial = new Multinomial($probabilities);
        $this->assertEquals($pmf, $multinomial->pmf($frequencies), '', 0.001);
    }

    /**
     * @return array
     */
    public function dataProviderForPMF(): array
    {
        return [
            [ [1, 1], [0.5, 0.5], 0.5 ],
            [ [1, 1], [0.4, 0.6], 0.48 ],
            [ [7, 2, 3], [0.40, 0.35, 0.25], 0.0248 ],
            [ [1, 2, 3], [0.2, 0.3, 0.5], 0.135 ],
            [ [2, 3, 3, 2], [0.25, 0.25, 0.25, 0.25], 0.024 ],
            [ [5, 2], [0.4, 0.6], 0.07741440000000005 ],
        ];
    }

    /**
     * @testCase pmf throws Exception\BadDataException if the number of frequencies does not match the number of probabilities
     */
    public function testPMFExceptionCountFrequenciesAndProbabilitiesDoNotMatch()
    {
        $probabilities = [0.3, 0.4, 0.2, 0.1];
        $frequencies   = [1, 2, 3];
        $multinomial   = new Multinomial($probabilities);

        $this->expectException(Exception\BadDataException::class);
        $multinomial->pmf($frequencies);
    }

    /**
     * @testCase constructor throws Exception\BadDataException if the probabilities do not add up to 1
     */
    public function testPMFExceptionProbabilitiesDoNotAddUpToOne()
    {
        $this->expectException(Exception\BadDataException::class);
        $multinomial = new Multinomial([0.3, 0.2, 0.1]);
    }
}
