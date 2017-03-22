<?php
namespace MathPHP;

class ArithmeticTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     cubeRoot returns the expected value.
     * @dataProvider dataProviderForCubeRoot
     * @param  number $x
     * @param  number $cube_root
     */
    public function testCubeRoot($x, $cube_root)
    {
        $this->assertEquals($cube_root, Arithmetic::cubeRoot($x), '', 0.000000001);
    }

    /**
     * @return array
     */
    public function dataProviderForCubeRoot(): array
    {
        return [
            [0, 0],
            [1, 1],
            [-1, -1],
            [2, 1.259921049894873],
            [-2, -1.259921049894873],
            [3, 1.442249570307408],
            [-3, -1.442249570307408],
            [8, 2],
            [-8, -2],
            [27, 3],
            [-27, -3],
            [64, 4],
            [-64, -4],
            [125, 5],
            [-125, -5],
            [245.362, 6.260405067916984],
            [-245.362, -6.260405067916984],
            [0.0548, 0.379833722265818],
            [-0.0548, -0.379833722265818],
        ];
    }
}
