<?php
namespace MathPHP\Tests\SetTheory;

use MathPHP\SetTheory\Set;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\LinearAlgebra\Matrix;

class SetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForSingleSet
     */
    public function testContstructor(array $members)
    {
        $set = new Set($members);
        $this->assertInstanceOf(Set::class, $set);
    }

    public function testInterfaces()
    {
        $interfaces = class_implements(Set::class);

        $this->assertContains('Countable', $interfaces);
        $this->assertContains('Iterator', $interfaces);
    }

    /**
     * @dataProvider dataProviderForAsArray
     */
    public function testAsArray(array $members, $expected)
    {
        $set = new Set($members);
        $this->assertEquals($expected, $set->asArray());
    }

    public function dataProviderForAsArray()
    {
        return [
            [
                [],
                [],
            ],
            [
                [0],
                [0 => 0],
            ],
            [
                [1],
                [1 => 1],
            ],
            [
                [5],
                [5 => 5],
            ],
            [
                [-5],
                ['-5' => -5],
            ],
            [
                [1, 2],
                [1 => 1, 2 => 2],
            ],
            [
                [1, 2, 3],
                [1 => 1, 2 => 2, 3 => 3],
            ],
            [
                [1, -2, 3],
                [1 => 1, '-2' => -2, 3 => 3],
            ],
            [
                [1, 2, 3, 4, 5, 6],
                [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10],
            ],
            [
                [1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 2, 2.01, 2.001, 2.15],
                [1 => 1, '1.1' => 1.1, '1.2' => 1.2, '1.3' => 1.3, '1.4' => 1.4, '1.5' => 1.5, '1.6' => 1.6, 2 => 2, '2.01' => 2.01, '2.001' => 2.001, '2.15' => 2.15],
            ],
            [
                ['a'],
                ['a' => 'a'],
            ],
            [
                ['a', 'b'],
                ['a' => 'a', 'b' => 'b'],
            ],
            [
                ['a', 'b', 'c', 'd', 'e'],
                ['a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd', 'e' => 'e'],
            ],
            [
                [1, 2, 'a', 'b', 3.14, 'hello', 'goodbye'],
                [1 => 1, 2 => 2, 'a' => 'a', 'b' => 'b', '3.14' => 3.14, 'hello' => 'hello', 'goodbye' => 'goodbye'],
            ],
            [
                [1, 2, 3, new Set([1, 2]), 'a', 'b'],
                [1 => 1, 2 => 2, 3 => 3, 'Set{1, 2}' => new Set([1, 2]), 'a' => 'a', 'b' => 'b'],
            ],
            [
                ['a', 1, 'b', new Set([1, 'b']), new Set([3, 4, 5,]), '4', 5],
                ['a' => 'a', 1 => 1, 'b' => 'b', 'Set{1, b}' => new Set([1, 'b']), 'Set{3, 4, 5}' => new Set([3, 4, 5,]), '4' => '4', 5 => 5],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSingleSet
     */
    public function testAsArrayAnotherWay(array $members)
    {
        $set   = new Set($members);
        $array = $set->asArray();

        $new_set = new Set($array);
        $this->assertEquals($new_set, $set);
    }

    /**
     * @dataProvider dataProviderForSingleSet
     */
    public function testLength(array $members)
    {
        $set = new Set($members);

        $this->assertEquals(count($members), $set->length());
    }

    /**
     * @dataProvider dataProviderForSingleSet
     */
    public function testIsEmpty(array $members)
    {
        $set = new Set($members);

        $this->assertEquals(empty($members), $set->isEmpty());
    }

    /**
     * @dataProvider dataProviderForSingleSetAtLeastOneMember
     */
    public function testIsMember(array $members)
    {
        $set = new Set($members);

        foreach ($members as $member) {
            $this->assertTrue($set->isMember($member));
        }
    }

    /**
     * @dataProvider dataProviderForSingleSet
     */
    public function testIsNotMember(array $members)
    {
        $set = new Set($members);

        $this->assertTrue($set->isNotMember('TotallNotAMember'));
        $this->assertTrue($set->isNotMember('99999123'));
        $this->assertTrue($set->isNotMember(99999123));
    }

    /**
     * @return array
     */
    public function dataProviderForSingleSet(): array
    {
        $fh     = fopen(__FILE__, 'r');
        $vector = new Vector([1, 2, 3]);
        $func   = function ($x) {
            return $x * 2;
        };

        return [
            [[]],
            [[0]],
            [[1]],
            [[5]],
            [[-5]],
            [[1, 2]],
            [[1, 2, 3]],
            [[1, -2, 3]],
            [[1, 2, 3, 4, 5, 6]],
            [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            [[1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 2, 2.01, 2.001, 2.15]],
            [['a']],
            [['a', 'b']],
            [['a', 'b', 'c', 'd', 'e']],
            [[1, 2, 'a', 'b', 3.14, 'hello', 'goodbye']],
            [[1, 2, 3, new Set([1, 2]), 'a', 'b']],
            [['a', 1, 'b', new Set([1, 'b'])]],
            [['a', 1, 'b', new Set([1, 'b']), '4', 5]],
            [['a', 1, 'b', new Set([1, 'b']), new Set([3, 4, 5]), '4', 5]],
            [[1, 2, 3, [1, 2], [2, 3, 4]]],
            [[1, 2, $fh, $vector, [4, 5], 6, 'a', $func, 12, new Set([4, 6, 7]), new Set(), 'sets']],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderForSingleSetAtLeastOneMember(): array
    {
        $fh     = fopen(__FILE__, 'r');
        $vector = new Vector([1, 2, 3]);
        $func   = function ($x) {
            return $x * 2;
        };

        return [
            [[0]],
            [[1]],
            [[5]],
            [[-5]],
            [[1, 2]],
            [[1, 2, 3]],
            [[1, -2, 3]],
            [[1, 2, 3, 4, 5, 6]],
            [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            [[1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 2, 2.01, 2.001, 2.15]],
            [['a']],
            [['a', 'b']],
            [['a', 'b', 'c', 'd', 'e']],
            [[1, 2, 'a', 'b', 3.14, 'hello', 'goodbye']],
            [[1, 2, 3, new Set([1, 2]), 'a', 'b']],
            [['a', 1, 'b', new Set([1, 'b'])]],
            [['a', 1, 'b', new Set([1, 'b']), '4', 5]],
            [['a', 1, 'b', new Set([1, 'b']), new Set([3, 4, 5]), '4', 5]],
            [[1, 2, 3, [1, 2], [2, 3, 4]]],
            [[1, 2, $fh, $vector, [4, 5], 6, 'a', $func, 12, new Set([4, 6, 7]), new Set(), 'sets']],
        ];
    }

    /**
     * @dataProvider dataProviderForSingleSet
     */
    public function testCount(array $A)
    {
        $set = new Set($A);

        $this->assertEquals(count($A), count($set));
        $this->assertEquals($set->length(), count($set));
    }

    /**
     * @dataProvider dataProviderForToString
     */
    public function testToString(array $members, string $expected)
    {
        $set  = new Set($members);

        $this->assertEquals($expected, $set->__toString());
    }

    public function dataProviderForToString()
    {
        $vector      = new Vector([1, 2, 3]);
        $vector_hash = spl_object_hash($vector);

        return [
            [
                [],
                'Ø',
            ],
            [
                [new Set()],
                'Set{Ø}',
            ],
            [
                [0],
                'Set{0}',
            ],
            [
                [1],
                'Set{1}',
            ],
            [
                [5],
                'Set{5}',
            ],
            [
                [-5],
                'Set{-5}',
            ],
            [
                [1, 2],
                'Set{1, 2}',
            ],
            [
                [1, 2, 3],
                'Set{1, 2, 3}',
            ],
            [
                [1, 2, 3, new Set()],
                'Set{1, 2, 3, Ø}',
            ],
            [
                [1, -2, 3],
                'Set{1, -2, 3}',
            ],
            [
                [1, 2, 3, 4, 5, 6],
                'Set{1, 2, 3, 4, 5, 6}',
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                'Set{1, 2, 3, 4, 5, 6, 7, 8, 9, 10}',
            ],
            [
                [1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 2, 2.01, 2.001, 2.15],
                'Set{1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 2, 2.01, 2.001, 2.15}',
            ],
            [
                ['a'],
                'Set{a}',
            ],
            [
                ['a', 'b'],
                'Set{a, b}',
            ],
            [
                ['a', 'b', 'c', 'd', 'e'],
                'Set{a, b, c, d, e}',
            ],
            [
                [1, 2, 'a', 'b', 3.14, 'hello', 'goodbye'],
                'Set{1, 2, a, b, 3.14, hello, goodbye}',
            ],
            [
                [1, 2, 3, new Set([1, 2]), 'a', 'b'],
                'Set{1, 2, 3, Set{1, 2}, a, b}',
            ],
            [
                ['a', 1, 'b', new Set([1, 'b']), new Set([3, 4, 5]), '4', 5],
                'Set{a, 1, b, Set{1, b}, Set{3, 4, 5}, 4, 5}',
            ],
            [
                [1, 2, new Set([1, 2, new Set([1, 2])])],
                'Set{1, 2, Set{1, 2, Set{1, 2}}}',
            ],
            [
                [1, 2, [1, 2, 3]],
                'Set{1, 2, Array(a:3:{i:0;i:1;i:1;i:2;i:2;i:3;})}',
            ],
            [
                [1, 2, [1, 2, 3], [1, 2, 3]],
                'Set{1, 2, Array(a:3:{i:0;i:1;i:1;i:2;i:2;i:3;})}',
            ],
            [
                [1, 2, $vector],
                "Set{1, 2, MathPHP\LinearAlgebra\Vector($vector_hash)}",
            ],
            [
                [1, 2, $vector, $vector],
                "Set{1, 2, MathPHP\LinearAlgebra\Vector($vector_hash)}",
            ],
        ];
    }

    public function testIteratorInterface()
    {
        $set = new Set([1, 2, 3, 4, 5]);

        $i = 1;
        foreach ($set as $key => $value) {
            $this->assertEquals($i, $key);
            $this->assertEquals($i, $value);
            $i++;
        }

        $set = new Set([new Set([1, 2]), new Set([3, 4])]);

        $i = 1;
        foreach ($set as $key => $value) {
            if ($i === 1) {
                $this->assertEquals(('Set{1, 2}'), $key);
                $this->assertEquals(new Set([1, 2]), $value);
            }
            if ($i === 2) {
                $this->assertEquals(('Set{3, 4}'), $key);
                $this->assertEquals(new Set([3, 4]), $value);
            }
            $i++;
        }
    }

    public function testFluentInterface()
    {
        $A = new Set();

        $A->add(1)
          ->add(2)
          ->add(3)
          ->remove(2)
          ->add(4)
          ->remove(1)
          ->addMulti([5, 6, 7])
          ->add(new Set([1, 2, 3]))
          ->removeMulti([5, 6]);

        $B = new Set([3, 4, 7, new Set([1, 2, 3])]);

        $this->assertEquals($B, $A);
    }
}
