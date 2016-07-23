<?php
namespace Math\Probability\Distribution\Discrete;

class MultinomialTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPMF
     */
    public function testPMF(array $frequencies, array $probabilities, $pmf)
    {
        $this->assertEquals($pmf, Multinomial::PMF($frequencies, $probabilities), '', 0.001);
    }

    public function dataProviderForPMF()
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

    public function testPMFExceptionCountFrequenciesAndProbabilitiesDoNotMatch()
    {
        $this->setExpectedException('\Exception');
        Multinomial::PMF([1, 2,3], [0.3, 0.4, 0.2, 0.1]);
    }

    public function testPMFExceptionProbabilitiesDoNotAddUpToOne()
    {
        $this->setExpectedException('\Exception');
        Multinomial::PMF([1, 2,3], [0.3, 0.2, 0.1]);
    }
}
