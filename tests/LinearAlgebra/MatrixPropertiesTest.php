<?php
namespace MathPHP\LinearAlgebra;

class MatrixPropertiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     isSquare returns true for square matrices.
     * @dataProvider dataProviderForIsSquare
     */
    public function testIsSquare(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->assertTrue($A->isSquare());
    }

    /**
     * @testCase     isSquare returns false for nonsquare matrices.
     * @dataProvider dataProviderForIsNotSquare
     */
    public function testIsSquareFalseNonSquareMatrix(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->assertFalse($A->isSquare());
    }

    public function dataProviderForIsSquare()
    {
        return [
            [
                [[1]]
            ],
            [
                [
                  [1, 2],
                  [2, 3],
                ]
            ],
            [
                [
                  [1, 2, 3],
                  [4, 5, 6],
                  [7, 8, 9],
                ]
            ],
        ];
    }

    /**
     * @testCase     isNotSquare returns true for nonsquare matrices.
     * @dataProvider dataProviderForIsNotSquare
     */
    public function testIsNotSquare(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->assertFalse($A->isSquare());
    }

    public function dataProviderForIsNotSquare()
    {
        return [
            [
                [[1,2]]
            ],
            [
                [
                  [1, 2, 4],
                  [2, 3, 5],
                ]
            ],
            [
                [
                  [1, 2, 3, 5],
                  [4, 5, 6, 5],
                  [7, 8, 9, 5],
                ]
            ],
            [
                [
                  [1, 2, 3],
                  [4, 5, 6],
                  [7, 8, 9],
                  [1, 2, 3],
                ]
            ],
        ];
    }

    /**
     * @testCase     isSymmetric returns true for symmetric matrices.
     * @dataProvider dataProviderForIsSymmetric
     */
    public function testIsSymmetric(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isSymmetric());
    }

    public function dataProviderForIsSymmetric()
    {
        return [
            [
                [[1]],
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                ]
            ],
            [
                [
                    [1, 7, 3],
                    [7, 4, -5],
                    [3, -5, 6],
                ],
            ],
        ];
    }

    /**
     * @testCase     isSymmetric returns false for nonsymmetric matrices.
     * @dataProvider dataProviderForIsNotSymmetric
     */
    public function testIsNotSymmetric(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isSymmetric());
    }

    public function dataProviderForIsNotSymmetric()
    {
        return [
            [
                [[1, 1]],
            ],
            [
                [
                    [1, 2],
                    [5, 3],
                ]
            ],
            [
                [
                    [1, 7, 3],
                    [7, 4, 5],
                    [-3, -5, 6],
                ],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [1, 2, 3, 4],
                ],
            ],
        ];
    }

    /**
     * @testCase     isSingular returns true for a singular matrix.
     * @dataProvider dataProviderForIsSingular
     */
    public function testIsSingular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isSingular());
    }

    /**
     * @testCase     isSingular returns false for a nonsingular matrix.
     * @dataProvider dataProviderForIsNonsingular
     */
    public function testIsSingularFalseForNonsingularMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isSingular());
    }

    public function dataProviderForIsSingular(): array
    {
        return [
            [
                [
                    [0],
                ],
            ],
            [
                [
                    [0, 0],
                    [0, 0],
                ]
            ],
            [
                [
                    [0, 0],
                    [0, 1],
                ]
            ],
            [
                [
                    [0, 0],
                    [1, 0],
                ]
            ],
            [
                [
                    [0, 0],
                    [1, 1],
                ]
            ],
            [
                [
                    [0, 1],
                    [0, 0],
                ]
            ],
            [
                [
                    [0, 1],
                    [0, 1],
                ]
            ],
            [
                [
                    [1, 0],
                    [0, 0],
                ]
            ],
            [
                [
                    [1, 0],
                    [1, 0],
                ]
            ],
            [
                [
                    [1, 1],
                    [0, 0],
                ]
            ],
            [
                [
                    [1, 1],
                    [1, 1],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
            ],
            [
                [
                    [1, 2, 1],
                    [-2, -3, 1],
                    [3, 5, 0],
                ],
            ],
            [
                [
                    [1, -1, 2],
                    [2, 1, 1],
                    [1, 1, 0],
                ],
            ],
            [
                [
                    [1, 0, 1],
                    [0, 1, -1],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 1, 4, 1, 2],
                    [0, 1, 2, 1, 1],
                    [0, 0, 0, 1, 2],
                    [1, -1, 0, 0, 2],
                    [2, 1, 6, 0, 1],
                ],
            ],
        ];
    }

    /**
     * @testCase     isNonsingular returns true for a nonsingular matrix.
     * @dataProvider dataProviderForIsNonsingular
     */
    public function testIsNonsingular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isNonsingular());
    }

    /**
     * @testCase     isInvertible returns true for a invertible matrix.
     * @dataProvider dataProviderForIsNonsingular
     */
    public function testIsInvertible(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isInvertible());
    }

    /**
     * @testCase     isNonsingular returns false for a singular matrix.
     * @dataProvider dataProviderForIsSingular
     */
    public function testIsNonsingularFalseForSingularMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isNonsingular());
    }

    /**
     * @testCase     isInvertible returns false for a non-invertible matrix.
     * @dataProvider dataProviderForIsSingular
     */
    public function testIsInvertibleFalseForNonInvertibleMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isInvertible());
    }

    public function dataProviderForIsNonsingular(): array
    {
        return [
            [
                [
                    [1],
                ],
            ],
            [
                [
                    [0, 1],
                    [1, 0],
                ]
            ],
            [
                [
                    [0, 1],
                    [1, 1],
                ]
            ],
            [
                [
                    [1, 0],
                    [0, 1],
                ]
            ],
            [
                [
                    [1, 0],
                    [1, 1],
                ]
            ],
            [
                [
                    [1, 1],
                    [0, 1],
                ]
            ],
            [
                [
                    [1, 1],
                    [1, 0],
                ]
            ],
            [
                [
                    [-7, -6, -12],
                    [5, 5, 7],
                    [1, 0, 4],
                ],
            ],
            [
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [3, 8],
                    [4, 6],
                ],
            ],
            [
                [
                    [4, 3],
                    [3, 2],
                ],
            ],
            [
                [
                    [6, 1, 1],
                    [4, -2, 5],
                    [2, 8, 7],
                ],
            ],
            [
                [
                    [1, 2, 0],
                    [-1, 1, 1],
                    [1, 2, 3],
                ],
            ],
            [
                [
                    [4, 6, 3, 2],
                    [3, 6, 5, 3],
                    [5, 7, 8, 6],
                    [5, 4, 3, 2],
                ],
            ],
            [
                [
                    [3, 2, 0, 1],
                    [4, 0, 1, 2],
                    [3, 0, 2, 1],
                    [9, 2, 3, 1],
                ],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [5, 6, 7, 8],
                    [2, 6, 4, 8],
                    [3, 1, 1, 2],
                ],
            ],
            [
                [
                    [7, 4, 2, 0],
                    [6, 3, -1, 2],
                    [4, 6, 2, 5],
                    [8, 2, -7, 1],
                ],
            ],
            [
                [
                  [-4, 3, 1, 5, -8],
                  [6, 0, 9, 2, 6],
                  [-1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
            ],
            [
                [
                  [4, 3, 1, 5, -8],
                  [6, 0, 9, 2, 6],
                  [-1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [-1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],

            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, 1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, 1, 3, 4, 0],
                  [5, 9, 7, -7, 1]
                ],
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, 1, 3, 4, 0],
                  [5, 9, 7, 7, 1]
                ],
            ],
            [
                [
                    [5, 2, 0, 0, -2],
                    [0, 1, 4, 3, 2],
                    [0, 0, 2, 6, 3],
                    [0, 0, 3, 4, 1],
                    [0, 0, 0, 0, 2],
                ],
            ],
            [
                [
                    [5, 2, 0, 0, 2],
                    [0, 1, 4, 3, 2],
                    [0, 0, 2, 6, 3],
                    [0, 0, 3, 4, 1],
                    [0, 0, 0, 0, 2],
                ],
            ],
            [
                [
                    [5, 2, 0, 0, -2],
                    [0, -1, 4, 3, 2],
                    [0, 0, 2, 6, 3],
                    [0, 0, 3, 4, 1],
                    [0, 0, 0, 0, 2],
                ],
            ],
            [
                [
                    [2, -9, 1, 8, 4],
                    [-10, -1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
            ],
            [
                [
                    [2, 9, 1, 8, 4],
                    [-10, -1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
            ],
            [
                [
                    [2, 9, 1, 8, 4],
                    [10, -1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
            ],
            [
                [
                    [2, 9, 1, 8, 4],
                    [10, 1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, -1, 0, 1],
                    [0, 0, 1994, -1, 1089],
                    [1, 0, 212, 726, -378],
                ],
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, -1, 1089],
                    [1, 0, 212, 726, -378],
                ],
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, 1, 1089],
                    [1, 0, 212, 726, -378],
                ],
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, 1, 1089],
                    [1, 0, 212, 726, 378],
                ],
            ],
            [
                [
                    [276,1,179,23, -9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, 1, 1089],
                    [1, 0, 212, 726, 378],
                ],
            ],
            [
               [
                    [1, 0, 3, 5, 1],
                    [0, 1, 5, 1, 0],
                    [0, 4, 0, 0, 2],
                    [2, 3, 1, 2, 0],
                    [1, 0, 0, 1, 1],
                ],
            ],
            [
                [
                    [2, 3, 4, 1, 3],
                    [6, 1, 3, 1, 2],
                    [6, 3, 1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, 3, 1, 2],
                    [6, 3, 1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, 1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, 8],
                    [2, 1, -2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, -1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, 8],
                    [2, 1, -2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, -1, -3],
                    [6, 1, -3, -1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, -8],
                    [2, 1, -2, 4, 2],
                ],
            ],
            [
                [
                    [2, 1, 2],
                    [1, 1, 1],
                    [2, 2, 5],
                ],
            ],
            [
                [
                    [1, 0, 2, -1],
                    [3, 0, 0, 5],
                    [2, 1, 4, -3],
                    [1, 0, 5, 0],
                ],
            ],
            [
                [
                    [1, 0, 2, 0, 0, 4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
            ],
            [
                [
                    [-1, 0, 2, 0, 0, 4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
            ],
            [
                [
                    [-1, 0, -2, 0, 0, 4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
            ],
            [
                [
                    [-1, 0, -2, 0, 0, -4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
            ],
            [
                [
                    [1, 1, 1, 1, 1,  1],
                    [1, 3, 1, 3, 1,  3],
                    [1, 1, 4, 1, 1,  4],
                    [1, 3, 1, 7, 1,  3],
                    [1, 1, 1, 1, 6,  1],
                    [1, 3, 4, 3, 1, 12]
                ],
            ],
            [
                [
                    [-1, 1, 1, 1, 1, 1],
                    [1, 3, 1, 3, 1,  3],
                    [1, 1, 4, 1, 1,  4],
                    [1, 3, 1, 7, 1,  3],
                    [1, 1, 1, 1, 6,  1],
                    [1, 3, 4, 3, 1, 12]
                ],
            ],
            [
                [
                    [-1, 1, 1, 1, 1, 1],
                    [1, 3, 1, 3, 1,  3],
                    [1, 1, 4, 1, 1,  4],
                    [1, -3, 1, 7, 1, 3],
                    [1, 1, 1, 1, 6,  1],
                    [1, 3, 4, 3, 1, 12]
                ],
            ],
            [
                [
                    [1, 0, 0, 0, 0, 0],
                    [0, 2, 0, 0, 0, 0],
                    [0, 0, 3, 0, 0, 0],
                    [0, 0, 0, 4, 0, 0],
                    [0, 0, 0, 0, 5, 0],
                    [0, 0, 0, 0, 0, 6]
                ],
            ],
            [
                [
                    [-1, 0, 0, 0, 0, 0],
                    [0, 2, 0, 0, 0, 0],
                    [0, 0, 3, 0, 0, 0],
                    [0, 0, 0, 4, 0, 0],
                    [0, 0, 0, 0, 5, 0],
                    [0, 0, 0, 0, 0, 6]
                ],
            ],
            [
                [
                    [-1, 0, 0, 0, 0, 0],
                    [0, 2, 0, 0, 0, 0],
                    [0, 0, 0, 4, 0, 0],
                    [0, 0, 3, 0, 0, 0],
                    [0, 0, 0, 0, 5, 0],
                    [0, 0, 0, 0, 0, 6]
                ],
            ],
            [
                [
                    [1, 0, 0, 0, 0, 0],
                    [1, 2, 0, 0, 0, 0],
                    [1, 0, 3, 0, 0, 0],
                    [1, 2, 0, 4, 0, 0],
                    [1, 0, 0, 0, 5, 0],
                    [1, 2, 3, 0, 0, 6],
                ],
            ],
            [
                [
                    [0, 1, 4, 3, 2, 3, 3, 4, 4],
                    [1, 0, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 0, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 0, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 0, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 0, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 0, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 0, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 0],
                ],
            ],
            [
                [
                    [2, 1, 4, 3, 2, 3, 3, 4, 4],
                    [1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 3, 4, 4],
                    [1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
            ],
            [
                [
                    [2, 1, 4, 3, 2, 3, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
            ],
        ];
    }

    /**
     * @testCase     isPositiveDefinite returns true for a positive definite square matrix.
     * @dataProvider dataProviderForIsPositiveDefinite
     * @param        array $A
     */
    public function testIsPositiveDefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isPositiveDefinite());
    }

    public function dataProviderForIsPositiveDefinite(): array
    {
        return [
            [
                [
                    [2, -1],
                    [-1, 2],
                ],
            ],
            [
                [
                    [1, -1],
                    [-1, 4],
                ],
            ],
            [
                [
                    [5, 2],
                    [2, 3],
                ],
            ],
            [
                [
                    [6, 4],
                    [4, 5],
                ],
            ],
            [
                [
                    [12, -12],
                    [-12, 96],
                ],
            ],
            [
                [
                    [2, -1, 0],
                    [-1, 2, -1],
                    [0, -1, 2],
                ],
            ],
            [
                [
                    [2, -1, 1],
                    [-1, 2, -1],
                    [1, -1, 2],
                ],
            ],
            [
                [
                    [1, 0, 0],
                    [0, 3, 0],
                    [0, 0, 2],
                ],
            ],
            [
                [
                    [3, -2, 0],
                    [-2, 2, 0],
                    [0, 0, 2],
                ],
            ],
            [
                [
                    [4, 1, -1],
                    [1, 2, 1],
                    [-1, 1, 2],
                ],
            ],
            [
                [
                    [9, -3, 3, 9],
                    [-3, 17, -1, -7],
                    [3, -1, 17, 15],
                    [9, -7, 15, 44],
                ],
            ],
            [
                [
                    [14, 4, 9],
                    [4, 14, -7],
                    [9, -7, 14],
                ],
            ],
            [
                [
                    [13, 0, -3],
                    [0, 9, 9],
                    [-3, 9, 10],
                ],
            ],
            [
                [
                    [14, -7, -13],
                    [-7, 6, 5],
                    [-13, 5, 14],
                ],
            ],
        ];
    }

    /**
     * @testCase     isPositiveDefinite returns false for a non positive definite square matrix.
     * @dataProvider dataProviderForIsNotPositiveDefinite
     * @param        array $A
     */
    public function testIsNotPositiveDefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isPositiveDefinite());
    }

    public function dataProviderForIsNotPositiveDefinite(): array
    {
        return [
            // Not square
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ],
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                    [3, 4],
                ],
            ],
            // Not symmetric
            [
                [
                    [2, -1, 1],
                    [-1, 2, -1],
                    [2, -1, 2],
                ],
            ],
            [
                [
                    [2, -1, 1],
                    [-4, 2, -1],
                    [1, -1, 2],
                ],
            ],
            [
                [
                    [9, -13, 3, 9],
                    [-3, 17, -1, -7],
                    [3, -1, 17, 15],
                    [9, -7, 15, 44],
                ],
            ],
            // Square and symmetric but fails determinate test
            [
                [
                    [0, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 4],
                    [4, 1],
                ]
            ],
            [
                [
                    [-1, 0],
                    [0, -3],
                ],
            ],
        ];
    }

    /**
     * @testCase     isPositiveSemidefinite returns true for a positive definite square matrix.
     * @dataProvider dataProviderForIsPositiveSemidefinite
     * @param        array $A
     */
    public function testIsPositiveSemidefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isPositiveSemidefinite());
    }

    public function dataProviderForIsPositiveSemidefinite(): array
    {
        return [
            [
                [
                    [0, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [1, 0],
                    [0, 2],
                ],
            ],
            [
                [
                    [1, 1],
                    [1, 1],
                ],
            ],
            [
                [
                    [2, -1],
                    [-1, 2],
                ],
            ],
            [
                [
                    [0, 0, 0],
                    [0, 3, 0],
                    [0, 0, 3],
                ],
            ],
            [
                [
                    [2, -1, -1],
                    [-1, 2, -1],
                    [-1, -1, 2],
                ],
            ],
            [
                [
                    [2, -1, 0],
                    [-1, 2, -1],
                    [0, -1, 2],
                ],
            ],
            [
                [
                    [2, -1, 1],
                    [-1, 2, -1],
                    [1, -1, 2],
                ],
            ],
            [
                [
                    [2, -1, 2],
                    [-1, 2, -1],
                    [2, -1, 2],
                ],
            ],
            [
                [
                    [9, -3, 3, 9],
                    [-3, 17, -1, -7],
                    [3, -1, 17, 15],
                    [9, -7, 15, 44],
                ],
            ],
        ];
    }

    /**
     * @testCase     isPositiveSemidefinite returns false for a non positive semidefinite square matrix.
     * @dataProvider dataProviderForIsNotPositiveSemidefinite
     * @param        array $A
     */
    public function testIsNotPositiveSemiDefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isPositiveSemidefinite());
    }

    public function dataProviderForIsNotPositiveSemidefinite(): array
    {
        return [
            // Square and symmetric but fails determinate test
            [
                [
                    [0, -4],
                    [-4, 0],
                ],
            ],
            [
                [
                    [1, 4],
                    [4, 1],
                ]
            ],
            [
                [
                    [-1, 0],
                    [0, -3],
                ],
            ],
        ];
    }

    /**
     * @testCase     isNegativeDefinite returns true for a negative definite square matrix.
     * @dataProvider dataProviderForIsNegativeDefinite
     * @param        array $A
     */
    public function testIsNegativeDefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isNegativeDefinite());
    }

    public function dataProviderForIsNegativeDefinite(): array
    {
        return [
            [
                [
                    [-1, 1],
                    [1, -2],
                ],
            ],
            [
                [
                    [-3, 0, 0],
                    [0, -2, 0],
                    [0, 0, -1],
                ],
            ],
        ];
    }

    /**
     * @testCase     isNegativeDefinite returns false for a non negative definite square matrix.
     * @dataProvider dataProviderForIsNotNegativeDefinite
     * @param        array $A
     */
    public function testIsNotNegativeDefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isNegativeDefinite());
    }

    public function dataProviderForIsNotNegativeDefinite(): array
    {
        return [
            // Square and symmetric but fails determinate test
            [
                [
                    [0, -4],
                    [-4, 0],
                ],
            ],
            [
                [
                    [1, 4],
                    [4, 1],
                ]
            ],
            [
                [
                    [1, 0],
                    [0, -3],
                ],
            ],
            [
                [
                    [-1, 4],
                    [4, -1],
                ]
            ],
        ];
    }

    /**
     * @testCase     isNegativeSemidefinite returns true for a negative semidefinite square matrix.
     * @dataProvider dataProviderForIsNegativeSemidefinite
     * @param        array $A
     */
    public function testIsNegativeSemidefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isNegativeSemidefinite());
    }

    public function dataProviderForIsNegativeSemidefinite(): array
    {
        return [
            [
                [
                    [0, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 0],
                    [0, -1],
                ],
            ],
            [
                [
                    [-1, -1],
                    [-1, -1],
                ],
            ],
        ];
    }

    /**
     * @testCase     isNegativeSemidefinite returns false for a non negative semidefinite square matrix.
     * @dataProvider dataProviderForIsNotNegativeSemidefinite
     * @param        array $A
     */
    public function testIsNotNegativeSemidefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isNegativeSemidefinite());
    }

    public function dataProviderForIsNotNegativeSemidefinite(): array
    {
        return [
            // Square and symmetric but fails determinate test
            [
                [
                    [0, -4],
                    [-4, 0],
                ],
            ],
            [
                [
                    [1, 4],
                    [4, 1],
                ]
            ],
            [
                [
                    [1, 0],
                    [0, -3],
                ],
            ],
        ];
    }

    /**
     * @testCase Non square matrix is not any definite.
     */
    public function testNonSquareMatrixIsNotAnyDefinite()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
        ]);

        $this->assertFalse($A->isPositiveDefinite());
        $this->assertFalse($A->isPositiveSemidefinite());
        $this->assertFalse($A->isNegativeDefinite());
        $this->assertFalse($A->isNegativeSemidefinite());
    }

    /**
     * @testCase Non symmetric square matrix is not any definite.
     */
    public function testNonSymmetricSquareMatrixIsNotAnyDefinite()
    {
        $A = new Matrix([
            [1, 2, 3],
            [9, 8, 4],
            [6, 2, 5],
        ]);

        $this->assertFalse($A->isPositiveDefinite());
        $this->assertFalse($A->isPositiveSemidefinite());
        $this->assertFalse($A->isNegativeDefinite());
        $this->assertFalse($A->isNegativeSemidefinite());
    }

    /**
     * @testCase     isSquareAndSymmetric returns true for square symmetric matrices
     * @dataProvider dataProviderForIsPositiveDefinite
     */
    public function testIsSquareAndSymmetric(array $A)
    {
        $A = MatrixFactory::create($A);

        $reflection_method = new \ReflectionMethod(Matrix::class, 'isSquareAndSymmetric');
        $reflection_method->setAccessible(true);

        $this->assertTrue($reflection_method->invoke($A));
    }

    /**
     * @testCase     isSquareAndSymmetric returns false for non square symmetric matrices
     * @dataProvider dataProviderForIsNotSquareAndSymmetric
     */
    public function testIsNotSquareAndSymmetric(array $A)
    {
        $A = MatrixFactory::create($A);

        $reflection_method = new \ReflectionMethod(Matrix::class, 'isSquareAndSymmetric');
        $reflection_method->setAccessible(true);

        $this->assertFalse($reflection_method->invoke($A));
    }

    public function dataProviderForIsNotSquareAndSymmetric(): array
    {
        return [
            // Not square
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ],
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                    [3, 4],
                ],
            ],
            // Not symmetric
            [
                [
                    [2, -1, 1],
                    [-1, 2, -1],
                    [2, -1, 2],
                ],
            ],
            [
                [
                    [2, -1, 1],
                    [-4, 2, -1],
                    [1, -1, 2],
                ],
            ],
            [
                [
                    [9, -13, 3, 9],
                    [-3, 17, -1, -7],
                    [3, -1, 17, 15],
                    [9, -7, 15, 44],
                ],
            ],
        ];
    }

    /**
     * @testCase     isUpperTriangular returns true for an upper triangular matrix
     * @dataProvider dataProviderForUpperTriangularMatrix
     * @param        array $A
     */
    public function testIsUpperTriangular(array $U)
    {
        $U = MatrixFactory::create($U);

        $this->assertTrue($U->isUpperTriangular());
    }

    /**
     * @testCase     isUpperTriangular returns false for a non upper triangular matrix
     * @dataProvider dataProviderForNotTriangularMatrix
     * @param        array $A
     */
    public function testIsNotUpperTriangular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isUpperTriangular());
    }

    public function dataProviderForUpperTriangularMatrix(): array
    {
        return [
            [
                [
                    [1],
                ],
            ],
            [
                [
                    [1, 1],
                    [0, 1],
                ]
            ],
            [
                [
                    [1, 2],
                    [0, 4],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [0, 4, 5],
                    [0, 0, 6],
                ],
            ],
            [
                [
                    [6, 5, 4],
                    [0, 8, 8],
                    [0, 0, 9],
                ],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [0, 4, 5, 6],
                    [0, 0, 6, 7],
                    [0, 0, 0, 8],
                ],
            ],
            [
                [
                    [-1, 0, 0, 0, 0, 0],
                    [0, 2, 0, 0, 0, 0],
                    [0, 0, 3, 0, 0, 0],
                    [0, 0, 0, 4, 0, 0],
                    [0, 0, 0, 0, 5, 0],
                    [0, 0, 0, 0, 0, 6],
                ],
            ],
            [
                [
                    [1, 0, 0, 0, 0, 0],
                    [0, 2, 0, 0, 0, 0],
                    [0, 0, 3, 0, 0, 0],
                    [0, 0, 0, 4, 0, 0],
                    [0, 0, 0, 0, 5, 0],
                    [0, 0, 0, 0, 0, 6],
                ],
            ],
        ];
    }

    public function dataProviderForNotTriangularMatrix(): array
    {
        return [
            [
                [
                    [0, 1],
                    [1, 0],
                ]
            ],
            [
                [
                    [0, 1],
                    [1, 1],
                ]
            ],
            [
                [
                    [1, 8],
                    [1, 1],
                ]
            ],
            [
                [
                    [1, 1],
                    [1, 0],
                ]
            ],
            [
                [
                    [-7, -6, -12],
                    [5, 5, 7],
                    [1, 0, 4],
                ],
            ],
            [
                [
                    [3, 8],
                    [4, 6],
                ],
            ],
            [
                [
                    [4, 3],
                    [3, 2],
                ],
            ],
            [
                [
                    [6, 1, 1],
                    [4, -2, 5],
                    [2, 8, 7],
                ],
            ],
            [
                [
                    [1, 2, 0],
                    [-1, 1, 1],
                    [1, 2, 3],
                ],
            ],
            [
                [
                    [4, 6, 3, 2],
                    [3, 6, 5, 3],
                    [5, 7, 8, 6],
                    [5, 4, 3, 2],
                ],
            ],
            [
                [
                    [3, 2, 0, 1],
                    [4, 0, 1, 2],
                    [3, 0, 2, 1],
                    [9, 2, 3, 1],
                ],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [5, 6, 7, 8],
                    [2, 6, 4, 8],
                    [3, 1, 1, 2],
                ],
            ],
            [
                [
                    [7, 4, 2, 0],
                    [6, 3, -1, 2],
                    [4, 6, 2, 5],
                    [8, 2, -7, 1],
                ],
            ],
            [
                [
                  [-4, 3, 1, 5, -8],
                  [6, 0, 9, 2, 6],
                  [-1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
            ],
            [
                [
                  [4, 3, 1, 5, -8],
                  [6, 0, 9, 2, 6],
                  [-1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [-1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],

            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, 1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, 1, 3, 4, 0],
                  [5, 9, 7, -7, 1]
                ],
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, 1, 3, 4, 0],
                  [5, 9, 7, 7, 1]
                ],
            ],
            [
                [
                    [5, 2, 0, 0, -2],
                    [0, 1, 4, 3, 2],
                    [0, 0, 2, 6, 3],
                    [0, 0, 3, 4, 1],
                    [0, 0, 0, 0, 2],
                ],
            ],
            [
                [
                    [5, 2, 0, 0, 2],
                    [0, 1, 4, 3, 2],
                    [0, 0, 2, 6, 3],
                    [0, 0, 3, 4, 1],
                    [0, 0, 0, 0, 2],
                ],
            ],
            [
                [
                    [5, 2, 0, 0, -2],
                    [0, -1, 4, 3, 2],
                    [0, 0, 2, 6, 3],
                    [0, 0, 3, 4, 1],
                    [0, 0, 0, 0, 2],
                ],
            ],
            [
                [
                    [2, -9, 1, 8, 4],
                    [-10, -1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
            ],
            [
                [
                    [2, 9, 1, 8, 4],
                    [-10, -1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
            ],
            [
                [
                    [2, 9, 1, 8, 4],
                    [10, -1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
            ],
            [
                [
                    [2, 9, 1, 8, 4],
                    [10, 1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, -1, 0, 1],
                    [0, 0, 1994, -1, 1089],
                    [1, 0, 212, 726, -378],
                ],
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, -1, 1089],
                    [1, 0, 212, 726, -378],
                ],
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, 1, 1089],
                    [1, 0, 212, 726, -378],
                ],
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, 1, 1089],
                    [1, 0, 212, 726, 378],
                ],
            ],
            [
                [
                    [276,1,179,23, -9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, 1, 1089],
                    [1, 0, 212, 726, 378],
                ],
            ],
            [
               [
                    [1, 0, 3, 5, 1],
                    [0, 1, 5, 1, 0],
                    [0, 4, 0, 0, 2],
                    [2, 3, 1, 2, 0],
                    [1, 0, 0, 1, 1],
                ],
            ],
            [
                [
                    [2, 3, 4, 1, 3],
                    [6, 1, 3, 1, 2],
                    [6, 3, 1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, 3, 1, 2],
                    [6, 3, 1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, 1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, 8],
                    [2, 1, -2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, -1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, 8],
                    [2, 1, -2, 4, 2],
                ],
            ],
            [
                [
                    [2, 3, -4, -1, -3],
                    [6, 1, -3, -1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, -8],
                    [2, 1, -2, 4, 2],
                ],
            ],
            [
                [
                    [2, 1, 2],
                    [1, 1, 1],
                    [2, 2, 5],
                ],
            ],
            [
                [
                    [1, 0, 2, -1],
                    [3, 0, 0, 5],
                    [2, 1, 4, -3],
                    [1, 0, 5, 0],
                ],
            ],
            [
                [
                    [1, 0, 2, 0, 0, 4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
            ],
            [
                [
                    [-1, 0, 2, 0, 0, 4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
            ],
            [
                [
                    [-1, 0, -2, 0, 0, 4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
            ],
            [
                [
                    [-1, 0, -2, 0, 0, -4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
            ],
            [
                [
                    [1, 1, 1, 1, 1,  1],
                    [1, 3, 1, 3, 1,  3],
                    [1, 1, 4, 1, 1,  4],
                    [1, 3, 1, 7, 1,  3],
                    [1, 1, 1, 1, 6,  1],
                    [1, 3, 4, 3, 1, 12]
                ],
            ],
            [
                [
                    [-1, 1, 1, 1, 1, 1],
                    [1, 3, 1, 3, 1,  3],
                    [1, 1, 4, 1, 1,  4],
                    [1, 3, 1, 7, 1,  3],
                    [1, 1, 1, 1, 6,  1],
                    [1, 3, 4, 3, 1, 12]
                ],
            ],
            [
                [
                    [-1, 1, 1, 1, 1, 1],
                    [1, 3, 1, 3, 1,  3],
                    [1, 1, 4, 1, 1,  4],
                    [1, -3, 1, 7, 1, 3],
                    [1, 1, 1, 1, 6,  1],
                    [1, 3, 4, 3, 1, 12]
                ],
            ],
            [
                [
                    [-1, 0, 0, 0, 0, 0],
                    [0, 2, 0, 0, 0, 0],
                    [0, 0, 0, 4, 0, 0],
                    [0, 0, 3, 0, 0, 0],
                    [0, 0, 0, 0, 5, 0],
                    [0, 0, 0, 0, 0, 6]
                ],
            ],
            [
                [
                    [0, 1, 4, 3, 2, 3, 3, 4, 4],
                    [1, 0, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 0, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 0, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 0, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 0, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 0, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 0, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 0],
                ],
            ],
            [
                [
                    [2, 1, 4, 3, 2, 3, 3, 4, 4],
                    [1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 3, 4, 4],
                    [1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
            ],
            [
                [
                    [2, 1, 4, 3, 2, 3, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
            ],
        ];
    }

    /**
     * @testCase     isLowerTriangular returns true for an upper triangular matrix
     * @dataProvider dataProviderForLowerTriangularMatrix
     * @param        array $A
     */
    public function testIsLowerTriangular(array $L)
    {
        $L = MatrixFactory::create($L);

        $this->assertTrue($L->isLowerTriangular());
    }

    /**
     * @testCase     isLowerTriangular returns false for a non upper triangular matrix
     * @dataProvider dataProviderForNotTriangularMatrix
     * @param        array $A
     */
    public function testIsNotLowerTriangular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isLowerTriangular());
    }

    public function dataProviderForLowerTriangularMatrix(): array
    {
        return [
            [
                [
                    [1],
                ],
            ],
            [
                [
                    [1, 0],
                    [1, 1],
                ],
            ],
            [
                [
                    [1, 0, 0],
                    [1, 1, 0],
                    [1, 1, 1],
                ],
            ],
            [
                [
                    [1, 0, 0],
                    [2, 3, 0],
                    [4, 5, 6],
                ],
            ],
            [
                [
                    [1, 0, 0, 0],
                    [1, 1, 0, 0],
                    [1, 1, 1, 0],
                    [1, 1, 1, 1],
                ],
            ],
            [
                [
                    [5, 0, 0, 0],
                    [-6, 1, 0, 0],
                    [4, 6, 8, 0],
                    [6, 7, 7, -1],
                ],
            ],
            [
                [
                    [1, 0, 0, 0, 0, 0],
                    [1, 2, 0, 0, 0, 0],
                    [1, 0, 3, 0, 0, 0],
                    [1, 2, 0, 4, 0, 0],
                    [1, 0, 0, 0, 5, 0],
                    [1, 2, 3, 0, 0, 6],
                ],
            ],
        ];
    }

    /**
     * @testCase     isTriangular returns true for a lower triangular matrix
     * @dataProvider dataProviderForLowerTriangularMatrix
     * @param        array $L
     */
    public function testIsTriangularForLowerTriangular(array $L)
    {
        $L = MatrixFactory::create($L);

        $this->assertTrue($L->isTriangular());
    }

    /**
     * @testCase     isTriangular returns true for an upper triangular matrix
     * @dataProvider dataProviderForUpperTriangularMatrix
     * @param        array $A
     */
    public function testIsTriangularForUpperTriangular(array $U)
    {
        $U = MatrixFactory::create($U);

        $this->assertTrue($U->isTriangular());
    }

    /**
     * @testCase     isTriangular returns false for a non triangular matrix
     * @dataProvider dataProviderForNotTriangularMatrix
     * @param        array $A
     */
    public function testIsNotTriangular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isTriangular());
    }
}
