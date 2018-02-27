<?php
namespace MathPHP\Tests\Arithmetic;

use MathPHP\Arithmetic;

/**
 * Tests of arithmetic axioms
 * These tests don't test specific functions,
 * but rather arithmetic axioms which in term make use of multiple functions.
 * If all the arithmetic math is implemented properly, these tests should
 * all work out according to the axioms.
 *
 * Axioms tested:
 *  - Digital root
 *    - dr(n) = n ⇔ n ∈ {0, 1, 2, 3, 4, 5, 6, 7, 8, 9}
 *    - dr(n) < n ⇔ n ≥ 10
 *    - dr(a+b) = dr(dr(a) + dr(b))
 *    - dr(a×b) = dr(dr(a) × dr(b))
 *    - dr(n) = 0 ⇔ n = 9m for m = 1, 2, 3 ⋯
 */
class ArithmeticAxiomsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Axiom: dr(n) = n ⇔ n ∈ {0, 1, 2, 3, 4, 5, 6, 7, 8, 9}
     * The digital root of n is n itself if and only if the number has exactly one digit.
     */
    public function testDigitalRootEqualsN()
    {
        for ($n = 0; $n < 10; $n++) {
            $this->assertEquals($n, Arithmetic::digitalRoot($n));
        }
    }

    /**
     * Axiom: dr(n) < n ⇔ n ≥ 10
     * The digital root of n is less than n if and only if the number is greater than or equal to 10.
     */
    public function testDigitalRootLessThanN()
    {
        for ($n = 10; $n <= 100; $n++) {
            $this->assertLessThan($n, Arithmetic::digitalRoot($n));
        }
    }

    /**
     * Axiom: dr(a+b) = dr(dr(a) + dr(b))
     * The digital root of a + b is digital root of the sum of the digital root of a and the digital root of b.
     * @dataProvider dataProviderDigitalRootArithmetic
     * @param        int $a
     * @param        int $b
     */
    public function testDigitalRootAddition(int $a, int $b)
    {
        $dr⟮a＋b⟯ = Arithmetic::digitalRoot($a + $b);
        $dr⟮dr⟮a⟯＋dr⟮b⟯⟯ = Arithmetic::digitalRoot(Arithmetic::digitalRoot($a) + Arithmetic::digitalRoot($b));

        $this->assertEquals($dr⟮a＋b⟯, $dr⟮dr⟮a⟯＋dr⟮b⟯⟯);
    }

    /**
     * Axiom: dr(a×b) = dr(dr(a) × dr(b))
     * The digital root of a × b is digital root of the product of the digital root of a and the digital root of b.
     * @dataProvider dataProviderDigitalRootArithmetic
     * @param        int $a
     * @param        int $b
     */
    public function testDigitalRootProduct(int $a, int $b)
    {
        $dr⟮ab⟯ = Arithmetic::digitalRoot($a * $b);
        $dr⟮dr⟮a⟯×dr⟮b⟯⟯ = Arithmetic::digitalRoot(Arithmetic::digitalRoot($a) * Arithmetic::digitalRoot($b));

        $this->assertEquals($dr⟮ab⟯, $dr⟮dr⟮a⟯×dr⟮b⟯⟯);
    }

    public function dataProviderDigitalRootArithmetic(): array
    {
        return [
            [0, 0],
            [1, 0],
            [0, 1],
            [1, 1],
            [1, 2],
            [2, 2],
            [5, 4],
            [16, 42],
            [10, 10],
            [8041, 2301],
            [241, 325],
            [48, 332],
            [89, 404804],
            [12345, 67890],
            [405, 3],
            [0, 34434],
            [398792873, 20598729038],
        ];
    }

    /**
     * Axiom: dr(n) = 0 ⇔ n = 9m for m = 1, 2, 3 ⋯
     * The digital root of a nonzero number is 9 if and only if the number is itself a multiple of 9.
     */
    public function testDigitalRootMultipleOfNine()
    {
        for ($n = 9; $n <= 900; $n += 9) {
            $this->assertEquals(9, Arithmetic::digitalRoot($n));
        }
    }
}
