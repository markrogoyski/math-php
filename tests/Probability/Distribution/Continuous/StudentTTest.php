<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\StudentT;

class StudentTTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($t, $ν, $pdf)
    {
        $studentT = new StudentT($ν);
        $this->assertEquals($pdf, $studentT->pdf($t), '', 0.00001);
    }

    public function dataProviderForPDF()
    {
        return [
            [1, 2, 0.192450089729875254836],
            [-1, 2, 0.192450089729875254836],

            [-5, 2, 0.007127781101],
            [-3.9, 2, 0.01400646997],
            [-1.1, 2, 0.1738771253],
            [-0.1, 2, 0.3509182168],
            [0, 2, 0.353553391],
            [0.1, 2, 0.3509182168],
            [2.9, 2, 0.02977308969],
            [5, 2, 0.007127781101],

            [-5, 6, 0.001220840981],
            [-1.4, 6, 0.1423079919],
            [0, 6, 0.382732772],
            [1, 6, 0.223142291],
            [2.9, 6, 0.0178279372],
            [5, 6, 0.001220840981],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($t, $ν, $cdf)
    {
        $studentT = new StudentT($ν);
        $this->assertEquals($cdf, $studentT->cdf($t), '', 0.00001);
    }

    public function dataProviderForCDF()
    {
        return [
            [1, 2, 0.788675134594812882255],
            [-1, 2, 0.211324865405187117745],
            [-2, 3, 0.069662984279421588424],

            [0, 2, 0.5],
            [0.1, 2, 0.5352672808],
            [2.9, 2, 0.9494099023],
            [5, 2, 0.9811252243],

            [0, 6, 0.5],
            [1, 6, 0.8220411581],
            [3.9, 6, 0.9960080137],
            [5, 6, 0.9987738291],

        ];
    }

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean($ν, $μ)
    {
        $studentT = new StudentT($ν);
        $this->assertEquals($μ, $studentT->mean());
    }

    public function dataProviderForMean()
    {
        return [
            [2, 0],
            [3, 0],
        ];
    }
    
    public function testMeanNAN()
    {
        $studentT = new StudentT(1);
        $this->assertNan($studentT->mean());
    }
    
    /**
     * @dataProvider dataProviderForInverse
     */
    public function testInverse($p, $ν, $x)
    {
        $studentT = new StudentT($ν);
        $this->assertEquals($x, $studentT->inverse($p));
    }

    public function dataProviderForInverse()
    {
        return [
            [.6, 1, 0.3249196962],
            [.6, 2, 0.2886751346],
        ];
    }
}
