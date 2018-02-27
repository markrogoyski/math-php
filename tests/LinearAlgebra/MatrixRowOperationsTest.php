<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\Exception;

class MatrixRowOperationsTest extends \PHPUnit\Framework\TestCase
{

    public function setUp()
    {
        $this->A = [
            [1, 2, 3],
            [2, 3, 4],
            [4, 5, 6],
        ];
        $this->matrix = MatrixFactory::create($this->A);
    }

    /**
     * @dataProvider dataProviderForRowInterchange
     */
    public function testRowInterchange(array $A, int $mᵢ, int $mⱼ, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->rowInterchange($mᵢ, $mⱼ));
    }

    public function dataProviderForRowInterchange()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 1,
                [
                    [2, 3, 4],
                    [1, 2, 3],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [5, 5],
                    [4, 4],
                    [2, 7],
                    [9, 0],
                ], 2, 3,
                [
                    [5, 5],
                    [4, 4],
                    [9, 0],
                    [2, 7],
                ]
            ],
            [
                [
                    [5, 5],
                    [4, 4],
                    [2, 7],
                    [9, 0],
                ], 1, 2,
                [
                    [5, 5],
                    [2, 7],
                    [4, 4],
                    [9, 0],
                ]
            ]
        ];
    }

    public function testRowInterchangeExceptionRowGreaterThanM()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->expectException(Exception\MatrixException::class);
        $A->rowInterchange(4, 5);
    }

    /**
     * @dataProvider dataProviderForRowMultiply
     */
    public function testRowMultiply(array $A, int $mᵢ, $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->rowMultiply($mᵢ, $k));
    }

    public function dataProviderForRowMultiply()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 5,
                [
                    [5, 10, 15],
                    [2, 3, 4],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 4,
                [
                    [1, 2, 3],
                    [8, 12, 16],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 2, 8,
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [24, 32, 40],
                ]
            ],
        ];
    }

    public function testRowMultiplyExceptionRowGreaterThanM()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->expectException(Exception\MatrixException::class);
        $A->rowMultiply(4, 5);
    }

    public function testRowMultiplyExceptionKIsZero()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->expectException(Exception\BadParameterException::class);
        $A->rowMultiply(2, 0);
    }

    /**
     * @dataProvider dataProviderForRowDivide
     */
    public function testRowDivide(array $A, int $mᵢ, $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->rowDivide($mᵢ, $k));
    }

    public function dataProviderForRowDivide()
    {
        return [
            [
                [
                    [2, 4, 8],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 2,
                [
                    [1, 2, 4],
                    [2, 3, 4],
                    [3, 4, 5],
                ]
            ],
        ];
    }

    public function testRowDivideExceptionRowGreaterThanM()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->expectException(Exception\MatrixException::class);
        $A->rowDivide(4, 5);
    }

    public function testRowDivideExceptionKIsZero()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->expectException(Exception\BadParameterException::class);
        $A->rowDivide(2, 0);
    }

    /**
     * @dataProvider dataProviderForRowAdd
     */
    public function testRowAdd(array $A, int $mᵢ, $mⱼ, int $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->rowAdd($mᵢ, $mⱼ, $k));
    }

    public function dataProviderForRowAdd()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 1, 2,
                [
                    [1, 2, 3],
                    [4, 7, 10],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 2, 3,
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [9, 13, 17],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 2, 4,
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [7, 12, 17],
                ]
            ],
        ];
    }

    public function testRowAddExceptionRowGreaterThanM()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->expectException(Exception\MatrixException::class);
        $A->rowAdd(4, 5, 2);
    }

    public function testRowAddExceptionKIsZero()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->expectException(Exception\BadParameterException::class);
        $A->rowAdd(1, 2, 0);
    }

    /**
     * @dataProvider dataProviderForRowAddScalar
     */
    public function testRowAddScalar(array $A, int $mᵢ, int $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->rowAddScalar($mᵢ, $k));
    }

    public function dataProviderForRowAddScalar()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 5,
                [
                    [6, 7, 8],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
            ],
        ];
    }

    public function testRowAddScalarExceptionRowGreaterThanM()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->expectException(Exception\MatrixException::class);
        $A->rowAddScalar(4, 5, 2);
    }

    /**
     * @dataProvider dataProviderForRowSubtract
     */
    public function testRowSubtract(array $A, int $mᵢ, $mⱼ, int $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->rowSubtract($mᵢ, $mⱼ, $k));
    }

    public function dataProviderForRowSubtract()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 1, 2,
                [
                    [1, 2, 3],
                    [0, -1, -2],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 2, 3,
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [-3, -5, -7],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 2, 4,
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [-1, -4, -7],
                ]
            ],
        ];
    }

    public function testRowSubtractExceptionRowGreaterThanM()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->expectException(Exception\MatrixException::class);
        $A->rowSubtract(4, 5, 2);
    }

    /**
     * @dataProvider dataProviderForRowSubtractScalar
     */
    public function testRowSubtractScalar(array $A, int $mᵢ, int $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->rowSubtractScalar($mᵢ, $k));
    }

    public function dataProviderForRowSubtractScalar()
    {
        return [
            [
                [
                    [6, 7, 8],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 5,
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
            ],
        ];
    }

    public function testRowSubtractScalarExceptionRowGreaterThanM()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->expectException(Exception\MatrixException::class);
        $A->rowSubtractScalar(4, 5, 2);
    }

    /**
     * @dataProvider dataProviderForRowExclude
     */
    public function testRowExclude(array $A, int $mᵢ, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->rowExclude($mᵢ));
    }

    public function dataProviderForRowExclude()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0,
                [
                    [2, 3, 4],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1,
                [
                    [1, 2, 3],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 2,
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ]
            ],
        ];
    }

    public function testRowExcludeExceptionRowDoesNotExist()
    {
        $this->expectException(Exception\MatrixException::class);
        $this->matrix->rowExclude(-5);
    }
}
