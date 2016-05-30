<?php
namespace Math\Arithmetic;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForSignum
     */
    public function testSignum($x, $sign)
    {
        $this->assertEquals($sign, Functions::signum($x));
    }

    /**
     * @dataProvider dataProviderForSignum
     */
    public function testSgn($x, $sign)
    {
        $this->assertEquals($sign, Functions::sgn($x));
    }

    public function dataProviderForSignum()
    {
        return [
        [ 0, 0 ],
        [ 1, 1 ], [ 0.5, 1 ], [ 1.5, 1 ], [ 4, 1 ], [ 123241.342, 1 ],
        [ -1, -1 ], [ -0.5, -1 ], [ -1.5, -1 ], [ -4, -1 ], [ -123241.342, -1 ],
        ];
    }

    /**
     * @dataProvider dataProviderForGamma
     */
    public function testGamma($n, $gamma)
    {
        $this->assertEquals($gamma, Functions::gamma($n), '', 0.01);
    }

    public function dataProviderForGamma()
    {
        return [
        [ 1, 1 ],
        [ 2, 1 ],
        [ 3, 2 ],
        [ 4, 6 ],
        [ 5, 24 ],
        [ 6, 120 ],
        [ 1.1, 0.951350769866873183629 ],
        [ 1.2, 0.91816874239976061064 ],
        [ 1.5, 0.88622692545275801365 ],
        [ 2.5, 1.32934038817913702047 ],
        [ 5.324, 39.54287866273389258523 ],
        [ 10.2, 570499.02784103598123 ],
        [ 0, \INF ],
        [ -1, -\INF ],
        [ -2, -\INF ],
        ];
    }
}
