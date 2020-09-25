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
     *   pls.model = pls2_nipals(X, Y, 2, scale=TRUE)
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
            ->columnExclude(6)
            ->columnExclude(5)
            ->columnExclude(4)
            ->columnExclude(3)
            ->columnExclude(1);
        self::$pls = new PLS(self::$X, self::$Y, true);
    }

    /**
     * @test         Construction
     * @throws       Exception\MathException
     */
    public function testConstruction()
    {
        // When
        $pls = new PLS(self::$X, self::$Y, true);

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

    public function getW()
    {
        // Given.
        $expected = [
            [-0.4770668,  0.01413703],
            [-0.4643040, -0.03817455],
            [ 0.3217142,  0.37286624],
            [ -0.4337710, -0.21556426],
            [ 0.3167445, -0.48216394],
            [ 0.1743495,  0.59339427],
            [-0.3666701,  0.47775186],
        ];

        // When
        $W = self::$pls->getW()->getMatrix();

        // Then
        $this->assertEquals($expected, $W, '', .00001);
    }

    public function getC()
    {
        // Given.
        $expected = [
            [ 0.454770, 0.03737499],
            [-0.430135, 0.25598916],
        ];

        // When
        $C = self::$pls->getC()->getMatrix();

        // Then
        $this->assertEquals($expected, $C, '', .00001);
    }

    public function getP()
    {
        // Given.
        $expected = [
            [-0.4830909  0.008542336],
            [-0.4731801 -0.101398021],
            [ 0.3706942  0.362571565],
            [-0.4418500 -0.185442628],
            [ 0.2779994 -0.501504936],
            [ 0.2450876  0.576659047],
            [-0.2948947  0.495757659],
        ];

        // When
        $P = self::$pls->getP()->getMatrix();

        // Then
        $this->assertEquals($expected, $P, '', .00001);
    }
}
