<?php

namespace MathPHP\Tests\Statistics\Multivariate;

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\SampleData;
use MathPHP\Statistics\Multivariate\PCA;

class PLSTest extends \PHPUnit\Framework\TestCase
{
    /** @var PLS */
    private static $pls;
    
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
}
