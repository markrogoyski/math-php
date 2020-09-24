<?php

namespace MathPHP\Tests\Statistics\Multivariate;

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\SampleData;
use MathPHP\Statistics\Multivariate\PLS;

class PLSTest extends \PHPUnit\Framework\TestCase
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
     *   pls.model = pls2_nipals(X, Y, 2)
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
        self::$pls = new PLS(self::$X, self::$Y);
    }

    /**
     * @test         Construction
     * @throws       Exception\MathException
     */
    public function testConstruction()
    {
        // When
        $pls = new PLS(self::$X, self::$Y);

        // Then
        $this->assertInstanceOf(PLS::class, $pLS);
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
            [-0.09926516,   3.5032307],
            [-0.03691542,   0.2855113],
            [-0.02788006,   0.9908024],
            [0.01941345,   -0.6949060],
            [0.30318932,  -10.7342649],
            [-0.10730878,   3.8048592],
            [-0.32849808 , 11.6319581],
        ];

        // When
        $B = self::$pls->getB();

        // Then
        $this->assertEquals($expected, $B, '', .00001);
    }
}
