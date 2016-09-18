<?php
namespace Math\LinearAlgebra;

class VectorNormsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForL1Norm
     */
    public function testL1Norm(array $A, $l₁norm)
    {
        $A = new Vector($A);

        $this->assertEquals($l₁norm, $A->l1Norm(), '', 0.0001);
    }

    public function dataProviderForL1Norm()
    {
        return [
            [ [1, 2, 3], 6 ],
            [ [-7, 5, 5], 17 ],
        ];
    }

    /**
     * @dataProvider dataProviderForL2Norm
     */
    public function testL2Norm(array $A, $l²norm)
    {
        $A = new Vector($A);

        $this->assertEquals($l²norm, $A->l2Norm(), '', 0.0001);
    }

    public function dataProviderForL2Norm()
    {
        return [
            [ [1, 2, 3], 3.7416573867739413 ],
            [ [7, 5, 5], 9.9498743710662 ],
        ];
    }

    /**
     * @dataProvider dataProviderForPNorm
     */
    public function testPNorm(array $A, $p, $pnorm)
    {
        $A = new Vector($A);

        $this->assertEquals($pnorm, $A->pNorm($p), '', 0.0001);
    }

    public function dataProviderForPNorm()
    {
        return [
            [ [1, 2, 3], 2, 3.74165738677 ],
            [ [1, 2, 3], 3, 3.30192724889 ],
        ];
    }

    /**
     * @dataProvider dataProviderForMaxNorm
     */
    public function testMaxNorm(array $A, $maxnorm)
    {
        $A = new Vector($A);

        $this->assertEquals($maxnorm, $A->maxNorm(), '', 0.0001);
    }

    public function dataProviderForMaxNorm()
    {
        return [
            [ [1, 2, 3], 3 ],
            [ [7, -5, 5], 7 ],
            [ [-3, -7, 6, 3], 7],
        ];
    }
}
