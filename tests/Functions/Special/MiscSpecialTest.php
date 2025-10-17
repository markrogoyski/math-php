<?php

namespace MathPHP\Tests\Functions\Special;

use MathPHP\Functions\Special;
use MathPHP\Exception;

class MiscSpecialTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         signum/sgn returns the expected value
     * @dataProvider dataProviderForSignum
     * @param        $x
     * @param        $sign
     */
    public function testSignum($x, $sign)
    {
        // When
        $signum = Special::signum($x);
        $sgn    = Special::sgn($x);

        // Then
        $this->assertEquals($sign, $signum);
        $this->assertEquals($sign, $sgn);
    }

    public function dataProviderForSignum(): array
    {
        return [
            [ 0, 0 ],
            [ 1, 1 ],
            [ 0.5, 1 ],
            [ 1.5, 1 ],
            [ 4, 1 ],
            [ 123241.342, 1 ],
            [ -1, -1 ],
            [ -0.5, -1 ],
            [ -1.5, -1 ],
            [ -4, -1 ],
            [ -123241.342, -1 ],
        ];
    }

    /**
     * @test         logistic returns the expected value
     * @dataProvider dataProviderForLogistic
     * @param        float $x₀
     * @param        float $L
     * @param        float $k
     * @param        float $x
     * @param        float $expected
     */
    public function testLogistic(float $x₀, float $L, float $k, float $x, float $expected)
    {
        // When
        $logistic = Special::logistic($x₀, $L, $k, $x);

        // Then
        $this->assertEqualsWithDelta($expected, $logistic, \abs($expected) * 1e-6 + 1e-14);
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
            // Test data created with R (sigmoid) logistic(x, k, x₀) where L = 1
            [0, 1, 1, 0, 0.5],
            [0, 1, 1, 1, 0.7310586],
            [1, 1, 1, 0, 0.2689414],
        ];
    }

    /**
     * @test         sigmoid
     * @dataProvider dataProviderForSigmoid
     * @param        float $x
     * @param        float $expected
     */
    public function testSigmoid(float $x, float $expected)
    {
        // When
        $sigmoid = Special::sigmoid($x);

        // Then
        $this->assertEqualsWithDelta($expected, $sigmoid, 0.0000001);
    }

    /**
     * Test data created with R (sigmoid) sigmoid(x)
     * @return array (x, sigmoid)
     */
    public function dataProviderForSigmoid(): array
    {
        return [
            [0, 0.5],
            [1, 0.7310586],
            [2, 0.8807971],
            [3, 0.9525741],
            [4, 0.9820138],
            [5, 0.9933071],
            [6, 0.9975274],
            [10, 0.9999546],
            [13, 0.9999977],
            [15, 0.9999997],
            [16, 0.9999999],
            [17, 1],
            [20, 1],
            [-1, 0.2689414],
            [-2, 0.1192029],
            [-3, 0.04742587],
            [-4, 0.01798621],
            [-5, 0.006692851],
            [-6, 0.002472623],
            [-10, 4.539787e-05],
            [-15, 3.059022e-07],
            [-20, 2.061154e-09],
            [0.5, 0.6224593],
            [5.5, 0.9959299],
            [-0.5, 0.3775407],
            [-5.5, 0.004070138],
        ];
    }

    /**
     * @test sigmoid returns the expected value
     * Sigmoid is just a special case of the logistic function.
     */
    public function testSigmoidSpecialCaseOfLogisitic()
    {
        $this->assertEquals(Special::logistic(1, 1, 1, 2), Special::sigmoid(1));
        $this->assertEquals(Special::logistic(1, 1, 2, 2), Special::sigmoid(2));
        $this->assertEquals(Special::logistic(1, 1, 3, 2), Special::sigmoid(3));
        $this->assertEquals(Special::logistic(1, 1, 4, 2), Special::sigmoid(4));
        $this->assertEquals(Special::logistic(1, 1, 4.6, 2), Special::sigmoid(4.6));
    }

    /**
     * @test Sigmoid overflow resistance
     * Tests that sigmoid handles extreme values without overflow
     * @dataProvider dataProviderForSigmoidOverflow
     * @param float $x
     * @param float $expected
     */
    public function testSigmoidOverflowResistance(float $x, float $expected)
    {
        // When
        $result = Special::sigmoid($x);

        // Then - should be finite and close to expected
        $this->assertTrue(is_finite($result), "Sigmoid($x) should be finite, got: $result");
        $this->assertEqualsWithDelta($expected, $result, 1e-6);
    }

    /**
     * Test data for sigmoid overflow/underflow cases
     * Numerical stability issue: for large negative t, exp(-t) overflows to INF, causing 1/(1+INF) = 0
     */
    public function dataProviderForSigmoidOverflow(): array
    {
        return [
            // Large positive values
            [100, 1.0],
            [500, 1.0],
            [1000, 1.0],

            // Large negative values - tests the overflow fix
            [-100, 3.720075976020836e-44],    // exp(-(-100)) would overflow, but exp(-100) doesn't
            [-500, 7.124576406741286e-218],   // This should not be 0
            [-1000, 0.0],                     // Underflows to 0 (acceptable for this extreme)

            // Moderate values (reference)
            [0, 0.5],
            [5, 0.9933071],
            [-5, 0.006692851],
        ];
    }

    /**
     * @test         softmax returns the expected value
     * @dataProvider dataProviderForSoftmax
     * @param        array $𝐳
     * @param        array $expected
     */
    public function testSoftmax(array $𝐳, array $expected)
    {
        // When
        $σ⟮𝐳⟯ⱼ = Special::softmax($𝐳);

        // Then
        $this->assertEqualsWithDelta($expected, $σ⟮𝐳⟯ⱼ, 0.00001);
        $this->assertEqualsWithDelta(1, \array_sum($σ⟮𝐳⟯ⱼ), 0.00001);
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
            // scipy.special.softmax
            // Single element
            [[0], [1.0]],
            [[1], [1.0]],
            [[-1], [1.0]],

            // Two elements
            [[0, 0], [0.5, 0.5]],
            [[1, 0], [0.7310585786300049, 0.2689414213699951]],
            [[1, 2], [0.2689414213699951, 0.7310585786300049]],
            [[-1, 1], [0.11920292202211755, 0.8807970779778823]],
            [[2, -2], [0.9820137900379085, 0.017986209962091555]],
            [[5, 5], [0.5, 0.5]],

            // Three elements
            [[0, 0, 0], [0.3333333333333333, 0.3333333333333333, 0.3333333333333333]],
            [[1, 2, 3], [0.09003057317038046, 0.24472847105479764, 0.6652409557748218]],
            [[3, 2, 1], [0.6652409557748218, 0.24472847105479764, 0.09003057317038046]],
            [[-1, 0, 1], [0.09003057317038046, 0.24472847105479764, 0.6652409557748218]],
            [[1, 1, 1], [0.3333333333333333, 0.3333333333333333, 0.3333333333333333]],
            [[10, 20, 30], [2.061060046209062e-09, 4.539786860886666e-05, 0.999954600070331]],
            [[-5, 0, 5], [4.5094041236354885e-05, 0.006692549116589288, 0.9932623568421745]],

            // Four elements
            [[1, 2, 3, 4], [0.03205860328008499, 0.08714431874203257, 0.23688281808991013, 0.6439142598879724]],
            [[4, 3, 2, 1], [0.6439142598879724, 0.23688281808991013, 0.08714431874203257, 0.03205860328008499]],
            [[0, 0, 0, 0], [0.25, 0.25, 0.25, 0.25]],
            [[-2, -1, 0, 1], [0.03205860328008499, 0.08714431874203257, 0.23688281808991013, 0.6439142598879724]],
            [[1.5, 2.5, 3.5, 4.5], [0.03205860328008499, 0.08714431874203257, 0.23688281808991013, 0.6439142598879724]],

            // Five elements
            [[1, 2, 3, 4, 5], [0.011656230956039605, 0.03168492079612427, 0.0861285444362687, 0.23412165725273662, 0.6364086465588308]],
            [[5, 4, 3, 2, 1], [0.6364086465588308, 0.23412165725273662, 0.0861285444362687, 0.03168492079612427, 0.011656230956039605]],
            [[0, 0, 0, 0, 0], [0.2, 0.2, 0.2, 0.2, 0.2]],
            [[-2, -1, 0, 1, 2], [0.011656230956039605, 0.03168492079612427, 0.0861285444362687, 0.23412165725273662, 0.6364086465588308]],
            [[0.5, 1.0, 1.5, 2.0, 2.5], [0.058012217397997876, 0.09564597678455912, 0.1576935563815933, 0.2599927206586828, 0.42865552877716695]],

            // Six elements
            [[1, 2, 3, 4, 5, 6], [0.00426977854528211, 0.011606461431184656, 0.03154963320110002, 0.08576079462509835, 0.233122009623613, 0.6336913225737218]],
            [[-3, -2, -1, 0, 1, 2], [0.00426977854528211, 0.011606461431184656, 0.03154963320110002, 0.08576079462509835, 0.233122009623613, 0.6336913225737218]],
            [[10, 10, 10, 10, 10, 10], [0.16666666666666666, 0.16666666666666666, 0.16666666666666666, 0.16666666666666666, 0.16666666666666666, 0.16666666666666666]],

            // Large values
            [[100, 200], [3.720075976020836e-44, 1.0]],
            [[-100, -200], [1.0, 3.720075976020836e-44]],

            // Float values
            [[0.1, 0.2, 0.3], [0.3006096053557273, 0.3322249935333473, 0.3671654011109255]],
            [[1.1, 2.2, 3.3], [0.07675080370222268, 0.2305721567927995, 0.6926770395049778]],
            [[-1.5, -0.5, 0.5, 1.5], [0.03205860328008499, 0.08714431874203257, 0.23688281808991013, 0.6439142598879724]],
            [[0.25, 0.5, 0.75, 1.0], [0.16529617667112, 0.21224449212702542, 0.27252732244308187, 0.3499320087587727]],

            // Seven elements
            [[1, 2, 3, 4, 5, 6, 7], [0.0015683003158864727, 0.004263082250240779, 0.011588259014055805, 0.03150015390138463, 0.08562629594379714, 0.23275640430228023, 0.632697504272355]],
            [[0, 1, 2, 3, 4, 5, 6], [0.0015683003158864727, 0.004263082250240779, 0.011588259014055805, 0.03150015390138463, 0.08562629594379714, 0.23275640430228023, 0.632697504272355]],

            // More variety
            [[2.5, 3.7, 1.2], [0.21773927462950293, 0.7229198504417934, 0.05934087492870374]],
            [[-10, -5, 0, 5, 10], [2.047265678602026e-09, 3.038411668836069e-07, 4.509402744260929e-05, 0.006692547069415927, 0.9932620530147088]],
            [[1.1, 1.2, 1.3, 1.4, 1.5, 1.6], [0.12792666707742376, 0.1413808321003141, 0.15624998401060317, 0.17268293827830353, 0.19084416143303312, 0.21091541710032233]],
            [[0.1, 0.2, 0.3, 0.4, 0.5], [0.1621203478685732, 0.17917069369265443, 0.1980142400405615, 0.21883957945767907, 0.2418551389405318]],
            [[5, 10, 15], [4.5094041236354885e-05, 0.006692549116589288, 0.9932623568421745]],
            [[-0.5, -0.25, 0, 0.25, 0.5], [0.11405072375140664, 0.1464440280884384, 0.18803785418768845, 0.24144538407641541, 0.3100220098960511]],

            // Eight elements
            [[1, 2, 3, 4, 5, 6, 7, 8], [0.0005766127696870058, 0.0015673960138976283, 0.004260624102577063, 0.011581577075929859, 0.03148199051039798, 0.08557692272813494, 0.23262219398733308, 0.6323326828120425]],
            [[0.5, 1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0], [0.012103426523097706, 0.019955176756987287, 0.03290052437982574, 0.054243794362206846, 0.0894328975684541, 0.14744992052145606, 0.24310382032676792, 0.4008104395612045]],

            // More edge cases
            [[7, 7, 7], [0.3333333333333333, 0.3333333333333333, 0.3333333333333333]],
            [[-7, -7, -7, -7], [0.25, 0.25, 0.25, 0.25]],
            [[0.01, 0.02, 0.03, 0.04], [0.24626259322575048, 0.24873757343424976, 0.25123742760737455, 0.2537624057326252]],
            [[100, 101, 102], [0.09003057317038046, 0.24472847105479764, 0.6652409557748218]],
            [[1.11, 2.22, 3.33, 4.44], [0.024283614572437554, 0.07368518972511638, 0.22358727398799225, 0.6784439217144539]],
        ];
    }

    /**
     * @test Softmax overflow/underflow resistance
     * Tests that softmax correctly handles very large and very small values
     * @dataProvider dataProviderForSoftmaxOverflow
     * @param array $input
     * @param array $expected
     */
    public function testSoftmaxOverflowResistance(array $input, array $expected)
    {
        // When
        $result = Special::softmax($input);

        // Then - check each element
        $this->assertEqualsWithDelta($expected, $result, 1e-10);

        // And - verify sum is 1.0
        $∑ = array_sum($result);
        $this->assertEqualsWithDelta(1.0, $∑, 1e-10);

        // And - verify no NaN or INF values
        foreach ($result as $value) {
            $this->assertTrue(is_finite($value), "Softmax result should be finite, got: $value");
        }
    }

    /**
     * Test data for softmax overflow/underflow cases
     * Generated using NumPy/SciPy (trusted reference)
     */
    public function dataProviderForSoftmaxOverflow(): array
    {
        return [
            // Large positive values - test overflow resistance
            [[1000, 1001, 1002], [9.00305731703804624e-02, 2.44728471054797642e-01, 6.65240955774821785e-01]],
            [[500, 501, 502], [9.00305731703804624e-02, 2.44728471054797642e-01, 6.65240955774821785e-01]],
            [[100, 101, 102], [9.00305731703804624e-02, 2.44728471054797642e-01, 6.65240955774821785e-01]],

            // Large negative values - test underflow resistance
            [[-1000, -999, -998], [9.00305731703804624e-02, 2.44728471054797642e-01, 6.65240955774821785e-01]],
            [[-500, -499, -498], [9.00305731703804624e-02, 2.44728471054797642e-01, 6.65240955774821785e-01]],
            [[-100, -99, -98], [9.00305731703804624e-02, 2.44728471054797642e-01, 6.65240955774821785e-01]],

            // Mixed large values
            [[-100, 0, 100], [1.38389652673673756e-87, 3.72007597602083612e-44, 1.00000000000000000e+00]],

            // Equal values (edge case)
            [[1, 1, 1], [3.33333333333333315e-01, 3.33333333333333315e-01, 3.33333333333333315e-01]],
        ];
    }

    /**
     * @test         chebyshevEval bounds condition error on n
     * @dataProvider dataProviderForChebyshevEvalNOutOfBounds
     * @param        int $n
     */
    public function testChebyshevEvalNOutOfBounds(int $n)
    {
        // Given
        $x = 1;
        $a = [2, 3];

        // And
        $chebyshevEval = new \ReflectionMethod(Special::Class, 'chebyshevEval');
        $chebyshevEval->setAccessible(true);

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        $chebyshevEval->invokeArgs(null, [$x, $a, $n]);
    }

    public function dataProviderForChebyshevEvalNOutOfBounds(): array
    {
        return [
            [-2],
            [1001],
        ];
    }

    /**
     * @test         chebyshevEval bounds condition error on x
     * @dataProvider dataProviderForChebyshevEvalXOutOfBounds
     * @param        float $x
     */
    public function testChebyshevEvalXOutOfBounds(float $x): void
    {
        // Given
        $n = 5;
        $a = [2, 3];

        // And
        $chebyshevEval = new \ReflectionMethod(Special::Class, 'chebyshevEval');
        $chebyshevEval->setAccessible(true);

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        $chebyshevEval->invokeArgs(null, [$x, $a, $n]);
    }

    public function dataProviderForChebyshevEvalXOutOfBounds(): array
    {
        return [
            [-1.2],
            [1.2],
        ];
    }

    /**
     * @test         stirlingError
     * @dataProvider dataProviderForSterlingError
     * @param        float $n
     * @param        float $expected
     */
    public function testStirlingError(float $n, float $expected): void
    {
        // When
        $stirlingError = Special::stirlingError($n);

        // Then
        $this->assertEqualsWithDelta($expected, $stirlingError, 0.000001);
    }

    /**
     * Test data produces with R library DPQmpfr: stirlerrM(n)
     * @return array
     */
    public function dataProviderForSterlingError(): array
    {
        return [
            [0.5, 0.1534264],
            [1, 0.08106147],
            [1.5, 0.05481412],
            [2, 0.0413407],
            [5, 0.01664469],
            [5.5, 0.01513497],
            [14, 0.00595137],
            [14.5, 0.005746217],
            [15, 0.005554734],

            [0.1, 0.5127401],
            [0.2, 0.3222939],
            [0.9, 0.08958191],
            [1.1, 0.07400292],
            [5.3, 0.0157048],
            [14.7, 0.005668061],

            [15.5, 0.005375599],
            [20, 0.00416632],
            [22.4, 0.003719991],
        ];
    }

    /**
     * @test stirlingError infinity at 0
     */
    public function testStirlingErrorInfinity(): void
    {
        // Given
        $n = 0;

        // When
        $stirlingError = Special::stirlingError($n);

        // Then
        $this->assertInfinite($stirlingError);
    }

    /**
     * @test         stirlingError NAN for negative values
     * @dataProvider dataProviderForSterlingErrorNan
     * @param        float $n
     */
    public function testStirlingErrorNan(float $n): void
    {
        // Then
        $this->expectException(Exception\NanException::class);

        // When
        $stirlingError = Special::stirlingError($n);
    }

    public function dataProviderForSterlingErrorNan(): array
    {
        return [
            [-0.1],
            [-1],
            [-10],
        ];
    }
}
