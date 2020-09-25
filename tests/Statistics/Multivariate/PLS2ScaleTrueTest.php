<?php

namespace MathPHP\Tests\Statistics\Multivariate;

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\SampleData;
use MathPHP\Statistics\Multivariate\PLS;

class PLS2ScaleTrueTest extends \PHPUnit\Framework\TestCase
{
    /** @var PLS */
    private static $pls;

    /** @var Matrix */
    private static $X;

    /** @var Matrix */
    private static $Y;

    /**
     * R code for expected values:
     *   library(chemometrics)
     *   X = mtcars[,c(2:3, 5:7, 10:11)]
     *   Y = mtcars[,c(1,4)]
     *   pls.model = pls2_nipals(X, Y, 2, scale=true)
     *
     * @throws Exception\MathException
     */
    public static function setUpBeforeClass()
    {
        $mtCars = new SampleData\MtCars();

        // Remove any categorical variables
        $continuous = MatrixFactory::create($mtCars->getData())
            ->columnExclude(8)
            ->columnExclude(7);
        // exclude mpg and hp.
        self::$X = $continuous->columnExclude(3)->columnExclude(0);
        
        // mpg and hp.
        self::$Y = $continuous
            ->columnExclude(8)
            ->columnExclude(7)
            ->columnExclude(4)
            ->columnExclude(3)
            ->columnExclude(1);
        self::$pls = new PLS(self::$X, self::$Y, TRUE);
    }

    /**
     * @test         Construction
     * @throws       Exception\MathException
     */
    public function testConstruction()
    {
        // When
        $pls = new PLS(self::$X, self::$Y, TRUE);

        // Then
        $this->assertInstanceOf(PLS::class, $pls);
    }

    /**
     * @test The class returns the correct values for B
     *
     * R code for expected values:
     *   pls.model$B
     */
    public function testB()
    {
        // Given
        $expected = [
            [-0.2143731,  0.22289146],
            [-0.2105791,  0.20363413],
            [ 0.1588566, -0.05241863],
            [-0.2034550,  0.14419053],
            [ 0.1246612, -0.26901292],
            [ 0.1007163,  0.07176686],
            [-0.1473158,  0.29083061],
        ];

        // When
        $B = self::$pls->getB()->getMatrix();

        // Then
        $this->assertEquals($expected, $B, '', .00001);
    }
}
