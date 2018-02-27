<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Categorical;
use MathPHP\Exception;

class CategoricalTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     Constructor throws a BadParameterException if k is <= 0
     * @dataProvider dataProviderForBadK
     * @param        int $k
     */
    public function testBadK(int $k)
    {
        $this->expectException(Exception\BadParameterException::class);
        $categorical = new Categorical($k, []);
    }

    /**
     * @return array
     */
    public function dataProviderForBadK(): array
    {
        return [
            [0],
            [-1],
            [-40],
        ];
    }

    /**
     * @testCase Constructor throws a BadDataException if there are no exactly k probabilities
     */
    public function testBadCount()
    {
        $k             = 3;
        $probabilities = [0.4, 0.6];
        $this->expectException(Exception\BadDataException::class);
        $categorical = new Categorical($k, $probabilities);
    }

    /**
     * @testCase Constructor throws a BadDataException if the probabilities do not add up to 1
     */
    public function testBadProbabilities()
    {
        $k             = 2;
        $probabilities = [0.3, 0.2];
        $this->expectException(Exception\BadDataException::class);
        $categorical = new Categorical($k, $probabilities);
    }

    /**
     * @testCase     pmf returns the expected probability for the category x
     * @dataProvider dataProviderForPmf
     * @param        int    $k
     * @param        array  $probabilities
     * @param        int    $x
     * @param        float  $pmf
     */
    public function testPmf(int $k, array $probabilities, $x, float $pmf)
    {
        $categorical = new Categorical($k, $probabilities);
        $this->assertEquals($pmf, $categorical->pmf($x));
    }

    /**
     * @return array
     */
    public function dataProviderForPmf(): array
    {
        return [
            [
                1,
                ['a' => 1],
                'a',
                1
            ],
            [
                2,
                ['a' => 0.4, 'b' => 0.6],
                'a',
                0.4
            ],
            [
                2,
                ['a' => 0.4, 'b' => 0.6],
                'b',
                0.6
            ],
            [
                3,
                ['a' => 0.3, 'b' => 0.2, 'c' => 0.5],
                'a',
                0.3
            ],
            [
                3,
                ['a' => 0.3, 'b' => 0.2, 'c' => 0.5],
                'b',
                0.2
            ],
            [
                3,
                ['a' => 0.3, 'b' => 0.2, 'c' => 0.5],
                'c',
                0.5
            ],
        ];
    }

    /**
     * @testCase pmf throws a BadDataException if x is not a valid category
     */
    public function testPmfException()
    {
        $k             = 2;
        $probabilities = [0.4, 0.6];
        $categorical   = new Categorical($k, $probabilities);

        $this->expectException(Exception\BadDataException::class);
        $p = $categorical->pmf(99);
    }

    /**
     * @testCase     mode returns the expected category name
     * @dataProvider dataProviderForMode
     * @param        int    $k
     * @param        array  $probabilities
     * @param        mixed  $mode
     */
    public function testMode(int $k, array $probabilities, $mode)
    {
        $categorical = new Categorical($k, $probabilities);
        $this->assertEquals($mode, $categorical->mode());
    }

    /**
     * @return array
     */
    public function dataProviderForMode(): array
    {
        return [
            [
                1,
                ['a' => 1],
                'a',
            ],
            [
                2,
                ['a' => 0.4, 'b' => 0.6],
                'b',
            ],
            [
                2,
                ['a' => 0.4, 'b' => 0.6],
                'b',
            ],
            [
                3,
                ['a' => 0.3, 'b' => 0.2, 'c' => 0.5],
                'c',
            ],
        ];
    }

    /**
     * @testCase __get returns the expected attributes
     */
    public function testGet()
    {
        $k             = 2;
        $probabilities = [0.4, 0.6];
        $categorical   = new Categorical($k, $probabilities);

        $this->assertSame($k, $categorical->k);
        $this->assertSame($probabilities, $categorical->probabilities);
    }

    /**
     * @testCase __get throws a BadDataException if the attribute does not exist
     */
    public function testGetException()
    {
        $k             = 2;
        $probabilities = [0.4, 0.6];
        $categorical   = new Categorical($k, $probabilities);

        $this->expectException(Exception\BadDataException::class);
        $does_not_exist = $categorical->does_not_exist;
    }
}
