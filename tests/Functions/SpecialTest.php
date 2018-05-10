<?php
namespace MathPHP\Tests\Functions;

use MathPHP\Functions\Special;
use MathPHP\Exception;

class SpecialTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     signum/sgn returns the expected value
     * @dataProvider dataProviderForSignum
     */
    public function testSignum($x, $sign)
    {
        $this->assertEquals($sign, Special::signum($x));
        $this->assertEquals($sign, Special::sgn($x));
    }

    public function dataProviderForSignum(): array
    {
        return [
        [ 0, 0 ],
        [ 1, 1 ], [ 0.5, 1 ], [ 1.5, 1 ], [ 4, 1 ], [ 123241.342, 1 ],
        [ -1, -1 ], [ -0.5, -1 ], [ -1.5, -1 ], [ -4, -1 ], [ -123241.342, -1 ],
        ];
    }

    /**
     * @testCase     gamma returns the expected value
     * @dataProvider dataProviderForGammaLanczos
     */
    public function testGamma($z, $Γ)
    {
        $this->assertEquals($Γ, Special::gamma($z), '', 0.001);
    }

    /**
     * @testCase     gammaLanczos returns the expected value
     * @dataProvider dataProviderForGammaLanczos
     */
    public function testGammaLanczos($z, $Γ)
    {
        $this->assertEquals($Γ, Special::gammaLanczos($z), '', 0.001);
    }

    public function dataProviderForGammaLanczos(): array
    {
        return [
            [0.1, 9.51350769866873183629],
            [0.2, 4.5908437119988030532],
            [0.3, 2.99156898768759062831],
            [0.4, 2.21815954375768822306],
            [0.5, 1.772453850905516027298],
            [0.6, 1.489192248812817102394],
            [0.7, 1.29805533264755778568],
            [0.8, 1.16422971372530337364],
            [0.9, 1.0686287021193193549],
            [1,   1],
            [1.0, 1],
            [1.1, 0.951350769866873183629],
            [1.2, 0.91816874239976061064],
            [1.3, 0.89747069630627718849],
            [1.4, 0.88726381750307528922],
            [1.5, 0.88622692545275801365],
            [1.6, 0.89351534928769026144],
            [1.7, 0.90863873285329044998],
            [1.8, 0.9313837709802426989091],
            [1.9, 0.96176583190738741941],
            [2,   1],
            [2.0, 1],
            [3,   2],
            [3.0, 2],
            [4,   6],
            [4.0, 6],
            [5,   24],
            [5.0, 24],
            [6,   120],
            [6.0, 120],
            [2.5, 1.32934038817913702047],
            [5.324, 39.54287866273389258523],
            [10.2, 570499.02784103598123],
            [0, \INF],
            [0.0, \INF],
            [-1, -\INF],
            [-2, -\INF],
            [-2.0, -\INF],
            [-0.1, -10.686287021193193549],
            [-0.4, -3.72298062203204275599],
            [-1.1, 9.7148063829029032263],
            [-1.2, 4.8509571405220973902],
        ];
    }

    /**
     * @testCase     gammaStirling returns the expected value
     * @dataProvider dataProviderForGammaStirling
     */
    public function testGammaStirling($n, $Γ)
    {
        $this->assertEquals($Γ, Special::gammaStirling($n), '', 0.01);
    }

    public function dataProviderForGammaStirling(): array
    {
        return [
        [ 1, 1 ],
        [ 1.0, 1 ],
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
        [ -2.0, -\INF ],
        ];
    }

    /**
     * @testCase     beta returns the expected value
     * @dataProvider dataProviderForBeta
     */
    public function testBeta($x, $y, float $beta)
    {
        $this->assertEquals($beta, Special::beta($x, $y), '', 0.001);
        $this->assertEquals($beta, Special::β($x, $y), '', 0.001);
    }

    public function dataProviderForBeta(): array
    {
        return [
            [ 1.5, 0, \INF ],
            [ 0, 1.5, \INF ],
            [ 0, 0, \INF ],
            [ 1, 1, 1 ],
            [ 1, 2, 0.5 ],
            [ 2, 1, 0.5 ],
            [ 1, 3, 0.33333 ],
            [ 3, 1, 0.33333 ],
            [ 1.5, 0.2, 4.4776093743471688104 ],
            [ 0.2, 1.5, 4.4776093743471688104 ],
            [ 0.1, 0.9, 10.16641 ],
            [ 0.9, 0.1, 10.16641 ],
            [ 3, 9, 0.002020202 ],
            [ 9, 3, 0.002020202 ],
        ];
    }

    /**
     * @testCase     logistic returns the expected value
     * @dataProvider dataProviderForLogistic
     */
    public function testLogistic($x₀, $L, $k, $x, $logistic)
    {
        $this->assertEquals($logistic, Special::logistic($x₀, $L, $k, $x), '', 0.001);
    }

    public function dataProviderForLogistic(): array
    {
        return [
            [0, 0, 0, 0, 0],
            [1, 1, 1, 1, 0.5],
            [2, 2, 2, 2, 1],
            [3, 2, 2, 2, 0.238405844044],
            [3, 4, 2, 2, 0.476811688088],
            [3, 4, 5, 2, 0.0267714036971],
            [3, 4, 5, 6, 3.99999877639],
            [2.1, 3.2, 1.5, 0.6, 0.305118287677],
        ];
    }

    /**
     * @testCase sigmoid returns the expected value
     * Sigmoid is just a special case of the logistic function.
     */
    public function testSigmoid()
    {
        $this->assertEquals(Special::logistic(1, 1, 1, 2), Special::sigmoid(1));
        $this->assertEquals(Special::logistic(1, 1, 2, 2), Special::sigmoid(2));
        $this->assertEquals(Special::logistic(1, 1, 3, 2), Special::sigmoid(3));
        $this->assertEquals(Special::logistic(1, 1, 4, 2), Special::sigmoid(4));
        $this->assertEquals(Special::logistic(1, 1, 4.6, 2), Special::sigmoid(4.6));
    }


    /**
     * @testCase     errorFunction returns the expected value
     * @dataProvider dataProviderForErrorFunction
     */
    public function testErrorFunction($x, $error)
    {
        $this->assertEquals($error, Special::errorFunction($x), '', 0.0001);
        $this->assertEquals($error, Special::erf($x), '', 0.0001);
    }

    public function dataProviderForErrorFunction(): array
    {
        return [
            [ 0, 0 ],
            [ 1, 0.8427007929497148693412 ],
            [ -1, -0.8427007929497148693412 ],
            [ 2, 0.9953222650189527341621 ],
            [ 3.4, 0.9999984780066371377146 ],
            [ 0.154, 0.1724063976196591819236 ],
            [ -2.31, -0.9989124231037000500402 ],
            [ -1.034, -0.856340111375020118952 ],
        ];
    }

    /**
     * @testCase     complementaryErrorFunction returns the expected value
     * @dataProvider dataProviderForComplementaryErrorFunction
     */
    public function testComplementaryErrorFunction($x, $error)
    {
        $this->assertEquals($error, Special::complementaryErrorFunction($x), '', 0.0001);
        $this->assertEquals($error, Special::erfc($x), '', 0.0001);
    }

    public function dataProviderForComplementaryErrorFunction(): array
    {
        return [
            [ 0, 1 ],
            [ 1, 0.1572992070502851306588 ],
            [ -1, 1.842700792949714869341 ],
            [ 2, 0.004677734981047265837931 ],
            [ 3.4, 1.521993362862285361757E-6 ],
            [ 0.154, 0.8275936023803408180764 ],
            [ -2.31, 1.99891242310370005004 ],
            [ -1.034, 1.856340111375020118952 ],
        ];
    }

    /**
     * @testCase     lowerIncompleteGamma returns the expected value
     * @dataProvider dataProviderForLowerIncompleteGamma
     */
    public function testLowerIncompleteGamma($s, $x, $lig)
    {
        $this->assertEquals($lig, Special::lowerIncompleteGamma($s, $x), '', 0.001);
    }

    public function dataProviderForLowerIncompleteGamma(): array
    {
        return [
            [1, 2, 0.864664716763387308106],
            [0.5, 4, 1.764162781524843359935],
            [2, 3, 0.800851726528544228083],
            [4.5, 2.3, 1.538974541742516805669],
            [7, 9.55, 603.9624331483414852868],
        ];
    }

    /**
     * @testCase     regularizedIncompleteBeta returns the expected value
     * @dataProvider dataProviderForRegularizedIncompleteBeta
     */
    public function testRegularizedIncompleteBeta($x, $a, $b, $rib)
    {
        $this->assertEquals($rib, Special::regularizedIncompleteBeta($x, $a, $b), '', 0.00001);
    }

    public function dataProviderForRegularizedIncompleteBeta(): array
    {
        return [
            [0.4, 1, 2, 0.64],
            [0.4, 1, 4, 0.87040],
            [0.4, 2, 4, 0.663040],
            [0.4, 4, 4, 0.2897920],
            [0.7, 6, 10, 0.99634748],
            [0.7, 7, 10, 0.99287048],
            [0.44, 3, 8.4, 0.90536083],
            [0.44, 3.5, 8.5, 0.86907356],
            [0.3, 2.5, 4.5, 0.40653902],
            [0.5, 1, 2, 0.750],
            [0.2, 3.4, 2.3, 0.02072722],
            [0.8, 3.4, 2.3, 0.84323132],
            [0.45, 12.45, 3.49, 0.00283809],
            [0.294, 0.23, 2.11, 0.88503883],
            [0.993, 0.23, 2.11, 0.99999612],
            [0.76, 4, 0.3, 0.08350803],
            [0.55, 2, 2.5, 0.67737732],
            [0.55, 2.5, 2, 0.47672251],
            [0.55, 3.5, 2, 0.31772153],
            [0.73, 3.5, 5, 0.97317839],
        ];
    }

    /**
     * @testCase regularizedIncompleteBeta throws an OutOfBoundsException if a is less than 0
     */
    public function testRegularizedIncompleteBetaExceptionALessThanZero()
    {
        $a = -1;
        $this->expectException(Exception\OutOfBoundsException::class);
        Special::regularizedIncompleteBeta(0.4, $a, 4);
    }

    /**
     * @testCase regularizedIncompleteBeta throws an OutOfBoundsException if x is out of bounds
     */
    public function testRegularizedIncompleteBetaExceptionXOutOfBounds()
    {
        $x = -1;
        $this->expectException(Exception\OutOfBoundsException::class);
        Special::regularizedIncompleteBeta($x, 4, 4);
    }

    /**
     * @testCase     incompleteBeta returns the expected value
     * @dataProvider dataProviderForIncompleteBeta
     */
    public function testIncompleteBeta($x, $a, $b, $ib)
    {
        $this->assertEquals($ib, Special::incompleteBeta($x, $a, $b), '', 0.0001);
    }

    public function dataProviderForIncompleteBeta(): array
    {
        return [
            [0.1, 1, 3, 0.09033333333333333333333],
            [0.4, 1, 3, 0.2613333333333333333333],
            [0.9, 1, 3, 0.333],
            [0.4, 1, 2, 0.32],
            [0.4, 1, 4, 0.2176],
            [0.4, 2, 4, 0.033152],
            [0.4, 4, 4, 0.002069942857142857142857],
            [0.7, 6, 10, 3.31784042288233100233E-5],
            [0.7, 7, 10, 1.23984824870524912587E-5],
            [0.44, 3, 8.4, 0.0022050133154863808046],
            [0.44, 3.5, 8.5, 0.00101547937082370450368],
            [0.3, 2.5, 4.5, 0.00873072257563537808667],
            [0.5, 1, 2, 0.375],
            [0.2, 3.4, 2.3, 9.94015483378346364195E-4],
            [0.8, 3.4, 2.3, 0.040438859297104036187],
            [0.45, 12.45, 3.49, 1.016239733540625974803E-6],
            [0.294, 0.23, 2.11, 3.082589637435583044388],
            [0.993, 0.23, 2.11, 3.48298583651202868119],
            [0.76, 4, 0.3, 0.1692673319857469933301],
            [0.55, 2, 2.5, 0.0774145505281552534703],
            [0.55, 2.5, 2, 0.05448257245698492387678],
            [0.55, 3.5, 2, 0.0201727956188770976315],
            [0.73, 3.5, 5, 0.00553077297647439276549],
        ];
    }

    /**
     * @testCase     upperIncompleteGamma returns the expected value
     * @dataProvider dataProviderForUpperIncompleteGamma
     */
    public function testUpperIncompleteGamma($s, $x, $uig)
    {
        $this->assertEquals($uig, Special::upperIncompleteGamma($s, $x), '', 0.0001);
    }

    public function dataProviderForUpperIncompleteGamma(): array
    {
        return [
            [0.0001, 1, 0.21939372],
            [1, 1, 0.3678794411714423215955],
            [1, 2, 0.135335283236612691894],
            [2, 2, 0.40600585],
            [3, 2.5, 1.08762623],
            [3.5, 2, 2.59147401],
            [4.6, 2, 12.30949802],
            [4, 2.6, 4.41600987],
            [2.7, 2.6, 0.68432904],
            [1.5, 2.5, 0.15225125],
        ];
    }

    /**
     * @testCase upperIncompleteGamma throws an OutOfBoundsException if s is less than 0
     */
    public function testUppderIncompleteGammaExceptionSLessThanZero()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Special::upperIncompleteGamma(-1, 1);
    }

    /**
     * @testCase generalizedHypergeometric throws a BadParameterException if the parameter count is wrong
     */
    public function testGeneralizedHypergeometricExceptionParameterCount()
    {
        $this->expectException(Exception\BadParameterException::class);
        Special::generalizedHypergeometric(2, 1, ...[6.464756838, 0.509199496, 0.241379523]);
    }
    
    /**
     * @testCase confluentHypergeometric returns the expected value
     * @dataProvider dataProviderForConfluentHypergeometric
     */
    public function testConfluentHypergeometric($a, $b, $z, $expected)
    {
        $actual = Special::confluentHypergeometric($a, $b, $z);
        $tol = .000001 * $expected;
        $this->assertEquals($expected, $actual, '', $tol);
    }

    public function dataProviderForConfluentHypergeometric(): array
    {
        return [
            [6.464756838, 0.509199496, 0.241379523, 6.48114845060471],
            [5.12297443791641, 5.26188297019653, 0.757399855727661, 2.09281409280936],
            [9.89309990528122, 6.92782493869175, 0.71686043176351, 2.73366255092188],
            [8.59824618495037, 6.66955518297157, 0.0293511981644408, 1.03854226944163],
        ];
    }
    
    /**
     * @testCase hypergeometric returns the expected value
     * @dataProvider dataProviderForHypergeometric
     */
    public function testHypergeometric($a, $b, $c, $z, $expected)
    {
        $actual = Special::hypergeometric($a, $b, $c, $z);
        $tol = .000001 * $expected;
        $this->assertEquals($expected, $actual, '', $tol);
    }

    public function dataProviderForHypergeometric(): array
    {
        return [
            [1, 1, 1, .9, 10],
            [1, 1, 1, .8, 5],
            [1, 1, 1, .7, 3.3333333],
            [3, 1.4, 1.5, .926, 1746.206366],
        ];
    }

    /**
     * @testCase hypergeometric throws an OutOfBoundsException if n is greater than 1
     */
    public function testHypergeometricExceptionNGreaterThanOne()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Special::hypergeometric(1, 1, 1, 1);
    }

    /**
     * @testCase softmax returns the expected value
     * @dataProvider dataProviderForSoftmax
     */
    public function testSoftmax(array $𝐳, array $expected)
    {
        $σ⟮𝐳⟯ⱼ = Special::softmax($𝐳);

        $this->assertEquals($expected, $σ⟮𝐳⟯ⱼ, '', 0.00001);

        $this->assertEquals(1, array_sum($σ⟮𝐳⟯ⱼ));
    }

    public function dataProviderForSoftmax(): array
    {
        return [
            // Test data: Wikipedia
            [
                [1, 2, 3, 4, 1, 2, 3],
                [0.023640543021591392, 0.064261658510496159, 0.17468129859572226, 0.47483299974438037, 0.023640543021591392, 0.064261658510496159, 0.17468129859572226],
            ],
            // Test data: http://www.kdnuggets.com/2016/07/softmax-regression-related-logistic-regression.html
            [
                [0.07, 0.22, 0.28],
                [0.29450637, 0.34216758, 0.363332605],
            ],
            [
                [0.35, 0.78, 1.12],
                [0.21290077, 0.32728332, 0.45981591],
            ],
            [
                [-0.33, -0.58, -0.92],
                [0.42860913, 0.33380113, 0.23758974],
            ],
            [
                [-0.39, -0.7, -1.1],
                [0.44941979, 0.32962558, 0.22095463],
            ],
            // Test data: https://martin-thoma.com/softmax/
            [
                [0.1, 0.2],
                [0.47502081, 0.52497919],
            ],
            [
                [-0.1, 0.2],
                [0.42555748, 0.57444252],
            ],
            [
                [0.9, -10],
                [0.999981542, 1.84578933e-05],
            ],
            [
                [0, 10],
                [4.53978687e-05, 0.999954602],
            ],
            // Test data: http://stackoverflow.com/questions/34968722/softmax-function-python
            [
                [3.0, 1.0, 0.2],
                [0.8360188, 0.11314284, 0.05083836],
            ],
            [
                [1, 2, 3],
                [0.09003057, 0.24472847, 0.66524096],
            ],
            [
                [2, 4, 8],
                [0.00242826, 0.01794253, 0.97962921],
            ],
            [
                [3, 5, 7],
                [0.01587624, 0.11731043, 0.86681333],
            ],
            [
                [6, 6, 6],
                [0.33333333, 0.33333333, 0.33333333],
            ],
            [
                [1, 2, 3, 6],
                [0.00626879, 0.01704033, 0.04632042, 0.93037047],
            ],
        ];
    }
}
