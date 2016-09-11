<?php
namespace Math\SetTheory;

class SetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForSingleSet
     */
    public function testContstructor(array $members)
    {
        $set = new Set($members);
        $this->assertInstanceOf('Math\SetTheory\Set', $set);
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
                [1 => 1, 2 => 2, 3 => 3, '{1, 2}' => new Set([1, 2]), 'a' => 'a', 'b' => 'b'],
            ],
            [
                ['a', 1, 'b', new Set([1, 'b']), new Set([3, 4, 5,]), '4', 5],
                ['a' => 'a', 1 => 1, 'b' => 'b', '{1, b}' => new Set([1, 'b']), '{3, 4, 5}' => new Set([3, 4, 5,]), '4' => '4', 5 => 5],
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

        $this->assertEquals(count($members), count($array));
        foreach ($members as $member) {
            $this->assertArrayHasKey("$member", $array);
        }

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
     * @dataProvider dataProviderForSingleSet
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

    public function dataProviderForSingleSet()
    {
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
        ];
    }

    /**
     * @dataProvider dataProviderForAdd
     */
    public function testAdd(array $A, $x, array $R)
    {
        $setA = new Set($A);
        $setR = new Set($R);

        $setA->add($x);

        $this->assertEquals($setR, $setA);
    }

    /**
     * @dataProvider dataProviderForAdd
     */
    public function testAddTwiceDoesNothing(array $A, $x, array $R)
    {
        $setA = new Set($A);
        $setR = new Set($R);

        $setA->add($x);
        $setA->add($x);

        $this->assertEquals($setR, $setA);
    }

    public function dataProviderForAdd()
    {
        return [
            [
                [],
                null,
                [],
            ],
            [
                [],
                [],
                [],
            ],
            [
                [],
                new Set(),
                ['Ø' => new Set()],
            ],
            [
                [],
                1,
                [1 => 1],
            ],
            [
                [],
                [1],
                [1],
            ],
            [
                [],
                [1, 2],
                [1, 2],
            ],
            [
                [1, 2, 3],
                [],
                [1, 2, 3],
            ],
            [
                [1, 2, 3],
                4,
                [1, 2, 3, 4],
            ],
            [
                [1, 2, 3],
                [4],
                [1, 2, 3, 4],
            ],
            [
                [1, 2, 3],
                [4, 5, 6],
                [1, 2, 3, 4, 5, 6],
            ],
            [
                [1, 2, 3],
                1,
                [1, 2, 3],
            ],
            [
                [1, 2, 3],
                [1, 2, 3],
                [1, 2, 3],
            ],
            [
                [1, 2, 3],
                [1, 2, 3, 4],
                [1, 2, 3, 4],
            ],
            [
                [1, 2, 3],
                'new',
                [1, 2, 3, 'new'],
            ],
            [
                [1, 2, 3],
                3.1,
                [1, 2, 3, 3.1],
            ],
            [
                [1, 2, 3],
                new Set(),
                [1, 2, 3, 'Ø'],
            ],
            [
                [1, 2, 3],
                new Set([4, 5]),
                [1, 2, 3, '{4, 5}'],
            ],
            [
                [1, 2, 3],
                new Set([1, 2]),
                [1, 2, 3, '{1, 2}'],
            ],
            [
                [1, 2, 3],
                [new Set([1, 2]), 6, 7, new Set([1, 2]), new Set([3, 4])],
                [1, 2, 3, '{1, 2}', 6, 7, '{3, 4}'],
            ],
            [
                [1, 2, 3],
                [-3],
                [1, 2, 3, -3],
            ],
        ];
    }

    public function testAddDoesNothingWithUnknownType()
    {
        $set    = new Set([1, 2, 3]);
        $vector = new \Math\LinearAlgebra\Vector([1, 2, 3]);

        $set->add($vector);

        $this->assertEquals(new Set([1, 2, 3]), $set);
    }

    /**
     * @dataProvider dataProviderForRemove
     */
    public function testRemove(array $A, $x, array $R)
    {
        $setA = new Set($A);
        $setR = new Set($R);

        $setA->remove($x);

        $this->assertEquals($setR, $setA);
    }

    public function dataProviderForRemove()
    {
        return [
            [
                [],
                [],
                [],
            ],
            [
                [],
                null,
                [],
            ],
            [
                [1],
                1,
                [],
            ],
            [
                [1],
                [1],
                [],
            ],
            [
                [1, 2, 3],
                1,
                [2, 3],
            ],
            [
                [1, 2, 3],
                2,
                [1, 3],
            ],
            [
                [1, 2, 3],
                3,
                [1, 2],
            ],
            [
                [1, 2, 3],
                [1],
                [2, 3],
            ],
            [
                [1, 2, 3],
                [2],
                [1, 3],
            ],
            [
                [1, 2, 3],
                [3],
                [1, 2],
            ],
            [
                [1, 2, 3],
                [1, 2],
                [3],
            ],
            [
                [1, 2, 3],
                [2, 3],
                [1],
            ],
            [
                [1, 2, 3],
                [1, 3],
                [2],
            ],
            [
                [1, 2, 3],
                5,
                [1, 2, 3],
            ],
            [
                [1, 2, 3],
                -1,
                [1, 2, 3],
            ],
            [
                [1, 2, 3, -3],
                -3,
                [1, 2, 3],
            ],
            [
                [1, 2, 3, 4.5, 6.7],
                4.5,
                [1, 2, 3, 6.7],
            ],
            [
                [1, 2, 3, 'a', 'b', 'see'],
                'b',
                [1, 2, 3, 'a', 'see'],
            ],
            [
                [1, 2, 3, 'a', 'b', 'see'],
                ['b', 1, 'see', 5555],
                [ 2, 3, 'a'],
            ],
            [
                [1, 2, 3, new Set([1, 2])],
                1,
                [2, 3, '{1, 2}'],
            ],
            [
                [1, 2, 3, new Set([1, 2])],
                new Set([1, 2]),
                [1, 2, 3],
            ],
            [
                [1, 2, 3, new Set([1, 2])],
                '{1, 2}',
                [1, 2, 3],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIsDisjoint
     */
    public function testIsDisjoint(array $A, array $B)
    {
        $setA = new Set($A);
        $setB = new Set($B);

        $this->assertTrue($setA->isDisjoint($setB));
    }

    public function dataProviderForIsDisjoint()
    {
        return [
            [
                [],
                [2],
            ],
            [
                [1],
                [],
            ],
            [
                [1],
                [2],
            ],
            [
                [1, 2, 3],
                [4, 5, 6],
            ],
            [
                [1, 2, 3,],
                [1.1, 2.2, -3],
            ],
            [
                [1, 2, 3, 'a', 'b'],
                [4, 5, 6, 'c', 'd'],
            ],
            [
                [1, 2, 3, new Set([1, 2])],
                [4, 5, 6],
            ],
            [
                [1, 2, 3, new Set([1, 2])],
                [4, 5, 6, new Set([2, 3])],
            ],
            [
                [new Set([1, 2])],
                [new Set([2, 3])],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForNotDisjoint
     */
    public function testNotDisjoint(array $A, array $B)
    {
        $setA = new Set($A);
        $setB = new Set($B);

        $this->assertFalse($setA->isDisjoint($setB));
    }

    public function dataProviderForNotDisjoint()
    {
        return [
            [
                [1],
                [1],
            ],
            [
                [new Set()],
                [new Set()],
            ],
            [
                [new Set([1, 2])],
                [new Set([1, 2])],
            ],
            [
                [1, 2, 3],
                [3, 4, 5],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIsSubsetSuperset
     */
    public function testIsSubset(array $A, array $B)
    {
        $setA = new Set($A);
        $setB = new Set($B);

        $this->assertTrue($setA->isSubset($setB));
    }

    public function dataProviderForIsSubsetSuperset()
    {
        return [
            [
                [],
                [1],
            ],
            [
                [1],
                [1],
            ],
            [
                [1, 2],
                [1, 2],
            ],
            [
                [1, 2],
                [1, 2, 3],
            ],
            [
                [1, 2, 'a'],
                [1, 2, 3, 'a', 4.5, new Set([1, 2])],
            ],
            [
                [1, 2, 'a', new Set([1, 2])],
                [1, 2, 3, 'a', 4.5, new Set([1, 2])],
            ],
            [
                [1, 2, 'a', new Set([1, 2]), -1, 2.4],
                [1, 2, 3, 'a', 4.5, new Set([1, 2]), -1, -2, 2.4, 3.5],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIsNotSubset
     */
    public function testIsNotSubset(array $A, array $B)
    {
        $setA = new Set($A);
        $setB = new Set($B);

        $this->assertFalse($setA->isSubset($setB));
    }

    public function dataProviderForIsNotSubset()
    {
        return [
            [
                [1],
                [],
            ],
            [
                [1, 2],
                [1],
            ],
            [
                [1, 2, 3],
                [1, 2],
            ],
            [
                [1, 2, 3, 4],
                [1, 2, 3],
            ],
            [
                [1, 2, 'b'],
                [1, 2, 3, 'a', 4.5, new Set([1, 2])],
            ],
            [
                [1, 2, 3, 'a', 4.5, new Set([1, 2])],
                [1, 2, 'a', new Set([1, 2])],
            ],
            [
                [1, 2, 3, 'a', 4.5, new Set([1, 2]), -1, -2, 2.4, 3.5],
                [1, 2, 'a', new Set([1, 2]), -1, 2.4],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIsProperSet
     */
    public function testIsProperSubset(array $A, array $B)
    {
        $setA = new Set($A);
        $setB = new Set($B);

        $this->assertTrue($setA->isProperSubset($setB));
    }

    /**
     * @dataProvider dataProviderForIsProperSet
     */
    public function testIsProperSuperset(array $A, array $B)
    {
        $setA = new Set($B);
        $setB = new Set($A);

        $this->assertFalse($setA->isProperSuperset($setB));
    }

    public function dataProviderForIsProperSet()
    {
        return [
            [
                [],
                [],
            ],
            [
                [1],
                [1],
            ],
            [
                [1, 2],
                [1, 2],
            ],
            [
                [1, 3, 2],
                [1, 2, 3],
            ],
            [
                [1, 2,'a', 3, 4.5, new Set([1, 2])],
                [1, 2, 3, 'a', 4.5, new Set([1, 2])],
            ],
            [
                [1, 2, 3, 'a', 4.5, new Set([1, 2])],
                [1, 2, 3, 'a', 4.5, new Set([1, 2])],
            ],
            [
                [1, 2, 3, 'a', 4.5, new Set([1, 2]), -1, -2, 2.4, 3.5],
                [1, 2, 3, 'a', 4.5, new Set([1, 2]), -1, -2, 2.4, 3.5],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIsSubsetSuperset
     */
    public function testIsSuperset(array $A, array $B)
    {
        $setA = new Set($B);
        $setB = new Set($A);

        $this->assertTrue($setA->isSuperset($setB));
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
     * @dataProvider dataProviderForUnion
     */
    public function testUnion(array $A, array $B, array $A∪B, Set $R)
    {
        $setA        = new Set($A);
        $setB        = new Set($B);
        $expected    = new Set($A∪B);
        $union       = $setA->union($setB);
        $union_array = $union->asArray();

        $this->assertEquals($R, $union);
        $this->assertEquals($expected, $union);
        $this->assertEquals(count($A∪B), count($union));
        foreach ($A∪B as $member) {
            $this->assertArrayHasKey("$member", $union_array);
        }
        foreach ($A∪B as $_ => $value) {
            if ($value instanceof Set) {
                $this->assertEquals($value, $union_array["$value"]);
            } else {
                $this->assertContains("$value", $union_array);
            }
        }
    }

    public function dataProviderForUnion()
    {
        return [
            [
                [],
                [],
                [],
                new Set(),
            ],
            [
                [1],
                [],
                [1],
                new Set([1]),
            ],
            [
                [],
                [1],
                [1],
                new Set([1]),
            ],
            [
                [1],
                [1],
                [1],
                new Set([1]),
            ],
            [
                [1],
                [2],
                [1, 2],
                new Set([1, 2]),
            ],
            [
                [2],
                [1],
                [1, 2],
                new Set([1, 2]),
            ],
            [
                [1],
                [2],
                [2, 1],
                new Set([1, 2]),
            ],
            [
                [2],
                [1],
                [2, 1],
                new Set([1, 2]),
            ],
            [
                [1, 2, 3, 'a', 'b'],
                [1, 'a', 'k'],
                [1, 2, 3, 'a', 'b', 'k'],
                new Set([1, 2, 3, 'a', 'b', 'k']),
            ],
            [
                [1, 2, 3, 'a', 'b', new Set([1, 2])],
                [1, 'a', 'k'],
                [1, 2, 3, 'a', 'b', 'k', new Set([1, 2])],
                new Set([1, 2, 3, 'a', 'b', 'k', new Set([1, 2])]),
            ],
            [
                [1, 2, 3, 'a', 'b'],
                [1, 'a', 'k', new Set([1, 2])],
                [1, 2, 3, 'a', 'b', 'k', new Set([1, 2])],
                new Set([1, 2, 3, 'a', 'b', 'k', new Set([1, 2])]),
            ],
            [
                [1, 2, 3, 'a', 'b', new Set()],
                [1, 'a', 'k', new Set([1, 2])],
                [1, 2, 3, 'a', 'b', 'k', new Set([1, 2]), new Set()],
                new Set([1, 2, 3, 'a', 'b', 'k', new Set([1, 2]), new Set()]),
            ],
            [
                [1, 2, 3, 'a', 'b', new Set([1, 2])],
                [1, 'a', 'k', -2, '2.4', 3.5, new Set([1, 2])],
                [1, 2, 3, 'a', 'b', 'k', -2, '2.4', 3.5, new Set([1, 2])],
                new Set([1, 2, 3, 'a', 'b', 'k', -2, '2.4', 3.5, new Set([1, 2])]),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForUnionMultipleSets
     */
    public function testUnionMultipleSets(array $A, array $B, array $C, array $A∪B∪C, Set $R)
    {
        $setA        = new Set($A);
        $setB        = new Set($B);
        $setC        = new Set($C);
        $expected    = new Set($A∪B∪C);
        $union       = $setA->union($setB, $setC);
        $union_array = $union->asArray();

        $this->assertEquals($R, $union);
        $this->assertEquals($expected, $union);
        $this->assertEquals(count($A∪B∪C), count($union));
        foreach ($A∪B∪C as $member) {
            $this->assertArrayHasKey("$member", $union_array);
        }
        foreach ($A∪B∪C as $_ => $value) {
            if ($value instanceof Set) {
                $this->assertEquals($value, $union_array["$value"]);
            } else {
                $this->assertContains("$value", $union_array);
            }
        }
    }

    public function dataProviderForUnionMultipleSets()
    {
        return [
            [
                [1, 2, 3],
                [2, 3, 4],
                [3, 4, 5],
                [1, 2, 3, 4, 5],
                new Set([1, 2, 3, 4, 5]),
            ],
            [
                [1, 2, 3, -3, 3.4],
                [2, 3, 4, new Set()],
                [3, 4, 5, new Set([1, 2])],
                [1, 2, 3, 4, 5, -3, 3.4, new Set(), new Set([1, 2])],
                new Set([1, 2, 3, 4, 5, -3, 3.4, new Set(), new Set([1, 2])]),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIntersect
     */
    public function testIntersect(array $A, array $B, array $A∩B, Set $R)
    {
        $setA               = new Set($A);
        $setB               = new Set($B);
        $expected           = new Set($A∩B);
        $intersection       = $setA->intersect($setB);
        $intersection_array = $intersection->asArray();

        $this->assertEquals($R, $intersection);
        $this->assertEquals($expected, $intersection);
        $this->assertEquals(count($A∩B), count($intersection));
        foreach ($A∩B as $member) {
            $this->assertArrayHasKey("$member", $intersection_array);
            $this->assertArrayHasKey("$member", $setA->asArray());
            $this->assertArrayHasKey("$member", $setB->asArray());
            $this->assertContains("$member", $A);
            $this->assertContains("$member", $B);
        }
        foreach ($A∩B as $_ => $value) {
            if ($value instanceof Set) {
                $this->assertEquals($value, $intersection_array["$value"]);
            } else {
                $this->assertContains("$value", $intersection_array);
            }
        }
    }

    public function dataProviderForIntersect()
    {
        return [
            [
                [],
                [],
                [],
                new Set(),
            ],
            [
                [1],
                [],
                [],
                new Set(),
            ],
            [
                [],
                [1],
                [],
                new Set(),
            ],
            [
                [1],
                [1],
                [1],
                new Set([1]),
            ],
            [
                [1],
                [2],
                [],
                new Set(),
            ],
            [
                [2],
                [1],
                [],
                new Set(),
            ],
            [
                [2],
                [2],
                [2],
                new Set([2]),
            ],
            [
                [1, 2],
                [1, 2],
                [1, 2],
                new Set([1, 2]),
            ],
            [
                [1, 2],
                [2, 1],
                [1, 2],
                new Set([1, 2]),
            ],
            [
                [1, 2, 3, 'a', 'b'],
                [1, 'a', 'k'],
                [1, 'a'],
                new Set([1, 'a']),
            ],
            [
                [1, 2, 3, 'a', 'b', new Set([1, 2])],
                [1, 'a', 'k'],
                [1, 'a'],
                new Set([1, 'a']),
            ],
            [
                [1, 2, 3, 'a', 'b'],
                [1, 'a', 'k', new Set([1, 2])],
                [1, 'a'],
                new Set([1, 'a']),
            ],
            [
                [1, 2, 3, 'a', 'b', new Set([1, 2])],
                [1, 'a', 'k', new Set([1, 2])],
                [1, 'a', new Set([1, 2])],
                new Set([1, 'a', new Set([1, 2])]),
            ],
            [
                [1, 2, 3, 'a', 'b', new Set()],
                [1, 'a', 'k', new Set([1, 2])],
                [1, 'a'],
                new Set([1, 'a']),
            ],
            [
                [1, 2, 3, 'a', 'b', new Set([1, 2])],
                [1, 'a', 'k', -2, '2.4', 3.5, new Set([1, 2])],
                [1, 'a', new Set([1, 2])],
                new Set([1, 'a', new Set([1, 2])]),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIntersectMultipleSets
     */
    public function testIntersectMultipleSets(array $A, array $B, array $C, array $A∩B∩C, Set $R)
    {
        $setA               = new Set($A);
        $setB               = new Set($B);
        $setC               = new Set($C);
        $expected           = new Set($A∩B∩C);
        $intersection       = $setA->intersect($setB, $setC);
        $intersection_array = $intersection->asArray();

        $this->assertEQuals($R, $intersection);
        $this->assertEquals($expected, $intersection);
        $this->assertEquals(count($A∩B∩C), count($intersection));
        foreach ($A∩B∩C as $member) {
            $this->assertArrayHasKey("$member", $intersection_array);
            $this->assertArrayHasKey("$member", $setA->asArray());
            $this->assertArrayHasKey("$member", $setB->asArray());
            $this->assertArrayHasKey("$member", $setC->asArray());
            $this->assertContains("$member", $A);
            $this->assertContains("$member", $B);
            $this->assertContains("$member", $C);
        }
        foreach ($A∩B∩C as $_ => $value) {
            if ($value instanceof Set) {
                $this->assertEquals($value, $intersection_array["$value"]);
            } else {
                $this->assertContains("$value", $intersection_array);
            }
        }
    }

    public function dataProviderForIntersectMultipleSets()
    {
        return [
            [
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                [3, 4, 5, 6],
                [3, 4],
                new Set([3, 4]),
            ],
            [
                [1, 2, 3, 4, new Set([1, 2])],
                [2, 3, 4, 5, new Set([1, 2])],
                [3, 4, 5, 6, new Set([1, 2])],
                [3, 4, new Set([1, 2])],
                new Set([3, 4, new Set([1, 2])]),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForDifference
     */
    public function testDifference(array $A, array $B, array $diff, Set $R)
    {
        $setA             = new Set($A);
        $setB             = new Set($B);
        $expected         = new Set($diff);
        $difference       = $setA->difference($setB);
        $difference_array = $difference->asArray();

        $this->assertEquals($R, $difference);
        $this->assertEquals($expected, $difference);
        $this->assertEquals(count($diff), count($difference));
        foreach ($diff as $member) {
            $this->assertArrayHasKey("$member", $difference_array);
            $this->assertArrayHasKey("$member", $setA->asArray());
            $this->assertArrayNotHasKey("$member", $setB->asArray());
            $this->assertContains("$member", $A);
            $this->assertNotContains("$member", $B);
        }
        foreach ($diff as $_ => $value) {
            if ($value instanceof Set) {
                $this->assertEquals($value, $difference_array["$value"]);
            } else {
                $this->assertContains("$value", $difference_array);
            }
        }
    }

    public function dataProviderForDifference()
    {
        return [
            [
                [],
                [],
                [],
                new Set(),
            ],
            [
                [1],
                [1],
                [],
                new Set(),
            ],
            [
                [1, 2],
                [1],
                [2],
                new Set([2]),
            ],
            [
                [1],
                [1, 2],
                [],
                new Set(),
            ],
            [
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                [1],
                new Set([1]),
            ],
            [
                [1, 2, 3, 'a', 'b'],
                [1, 'a', 'k'],
                [2, 3, 'b'],
                new Set([2, 3, 'b']),
            ],
            [
                [1, 2, 3, 'a', 'b', new Set([1, 2])],
                [1, 'a', 'k'],
                [2, 3, 'b',new Set([1, 2])],
                new Set([2, 3, 'b',new Set([1, 2])]),
            ],
            [
                [1, 2, 3, 'a', 'b'],
                [1, 'a', 'k', new Set([1, 2])],
                [2, 3, 'b'],
                new Set([2, 3, 'b']),
            ],
            [
                [1, 2, 3, 'a', 'b', new Set()],
                [1, 'a', 'k', new Set([1, 2])],
                [2, 3, 'b', new Set()],
                new Set([2, 3, 'b', new Set()]),
            ],
            [
                [1, 2, 3, 'a', 'b', new Set([1, 2])],
                [1, 'a', 'k', -2, '2.4', 3.5, new Set([1, 2])],
                [2, 3, 'b'],
                new Set([2, 3, 'b']),
            ],
            [
                [1, 2,'a', 3, 4.5, new Set([1, 2])],
                [1, 2, 3, 'a', 4.5, new Set([1, 2])],
                [],
                new Set(),
            ],
            [
                [1, 2, 3, 'a', 4.5, new Set([1, 2])],
                [1, 2, 3, 'a', 4.5, new Set([1, 2])],
                [],
                new Set(),
            ],
            [
                [1, 2, 3, 'a', 4.5, new Set([1, 2]), -1, -2, 2.4, 3.5],
                [1, 2, 3, 'a', 4.5, new Set([1, 2]), -1, -2, 2.4, 3.5],
                [],
                new Set(),
            ],

        ];
    }

    /**
     * @dataProvider dataProviderForDifferenceMultiSet
     */
    public function testDifferenceMultiSet(array $A, array $B, array $C, array $diff, Set $R)
    {
        $setA             = new Set($A);
        $setB             = new Set($B);
        $setC             = new Set($C);
        $expected         = new Set($diff);
        $difference       = $setA->difference($setB, $setC);
        $difference_array = $difference->asArray();

        $this->assertEquals($R, $difference);
        $this->assertEquals($expected, $difference);
        $this->assertEquals(count($diff), count($difference));
        foreach ($diff as $member) {
            $this->assertArrayHasKey("$member", $difference_array);
            $this->assertArrayHasKey("$member", $setA->asArray());
            $this->assertArrayNotHasKey("$member", $setB->asArray());
            $this->assertArrayNotHasKey("$member", $setC->asArray());
            $this->assertContains("$member", $A);
            $this->assertNotContains("$member", $B);
            $this->assertNotContains("$member", $C);
        }
        foreach ($diff as $_ => $value) {
            if ($value instanceof Set) {
                $this->assertEquals($value, $difference_array["$value"]);
            } else {
                $this->assertContains("$value", $difference_array);
            }
        }
    }

    public function dataProviderForDifferenceMultiSet()
    {
        return [
            [
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                [3, 4, 5, 6],
                [1],
                new Set([1]),
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                [2, 4, 6, 8, 10],
                [5, 10],
                [1, 3, 7, 9],
                new Set([1, 3, 7, 9]),
            ],
            [
                [1, 2, 3, 4, new Set([1, 2])],
                [2, 3, 4, 5],
                [3, 4, 5, 6],
                [1, new Set([1, 2])],
                new Set([1, new Set([1, 2])]),
            ],
            [
                [1, 2, 3, 4, new Set([1, 2])],
                [2, 3, 4, 5],
                [3, 4, 5, 6, new Set([1, 2])],
                [1],
                new Set([1]),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSymmetricDifference
     */
    public function testSymmetricDifference(array $A, array $B, array $diff, Set $R)
    {
        $setA             = new Set($A);
        $setB             = new Set($B);
        $expected         = new Set($diff);
        $difference       = $setA->symmetricDifference($setB);
        $difference_array = $difference->asArray();

        $this->assertEquals($R, $difference);
        $this->assertEquals($expected, $difference);
        $this->assertEquals(count($diff), count($difference));
        foreach ($diff as $member) {
            $this->assertArrayHasKey("$member", $difference_array);
        }
        foreach ($diff as $_ => $value) {
            if ($value instanceof Set) {
                $this->assertEquals($value, $difference_array["$value"]);
            } else {
                $this->assertContains("$value", $difference_array);
            }
        }
    }

    public function dataProviderForSymmetricDifference()
    {
        return [
            [
                [1, 2, 3],
                [2, 3, 4],
                [1, 4],
                new Set([1, 4]),
            ],
            [
                [1, 2, 3, new Set()],
                [2, 3, 4],
                [1, 4, new Set()],
                new Set([1, 4, new Set()]),
            ],
            [
                [1, 2, 3],
                [2, 3, 4, new Set()],
                [1, 4, new Set()],
                new Set([1, 4, new Set()]),
            ],
            [
                [1, 2, 3, new Set()],
                [2, 3, 4, new Set()],
                [1, 4],
                new Set([1, 4]),
            ],
            [
                [1, 3, 5, 7, 9],
                [2, 4, 6, 8, 10],
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                new Set([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSingleSet
     */
    public function testCopy(array $members)
    {
        $set  = new Set($members);
        $copy = $set->copy();

        $set_array  = $set->asArray();
        $copy_array = $copy->asArray();

        $this->assertEquals($set, $copy);
        $this->assertEquals($set_array, $copy_array);
        foreach ($members as $member) {
            $this->assertArrayHasKey("$member", $set_array);
            $this->assertArrayHasKey("$member", $copy_array);
        }
        foreach ($members as $_ => $value) {
            if ($value instanceof Set) {
                $this->assertEquals($value, $set_array["$value"]);
                $this->assertEquals($value, $copy_array["$value"]);
            } else {
                $this->assertContains("$value", $set_array);
                $this->assertContains("$value", $copy_array);
            }
        }
    }

    /**
     * @dataProvider dataProviderForSingleSet
     */
    public function testClear(array $members)
    {
        $set  = new Set($members);
        $set->clear();

        $this->assertTrue($set->isEmpty());
        $this->assertEmpty($set->asArray());
        $this->assertEquals($set, new Set());
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
        return [
            [
                [],
                'Ø',
            ],
            [
                [new Set()],
                '{Ø}',
            ],
            [
                [0],
                '{0}',
            ],
            [
                [1],
                '{1}',
            ],
            [
                [5],
                '{5}',
            ],
            [
                [-5],
                '{-5}',
            ],
            [
                [1, 2],
                '{1, 2}',
            ],
            [
                [1, 2, 3],
                '{1, 2, 3}',
            ],
            [
                [1, 2, 3, new Set()],
                '{1, 2, 3, Ø}',
            ],
            [
                [1, -2, 3],
                '{1, -2, 3}',
            ],
            [
                [1, 2, 3, 4, 5, 6],
                '{1, 2, 3, 4, 5, 6}',
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                '{1, 2, 3, 4, 5, 6, 7, 8, 9, 10}',
            ],
            [
                [1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 2, 2.01, 2.001, 2.15],
                '{1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 2, 2.01, 2.001, 2.15}',
            ],
            [
                ['a'],
                '{a}',
            ],
            [
                ['a', 'b'],
                '{a, b}',
            ],
            [
                ['a', 'b', 'c', 'd', 'e'],
                '{a, b, c, d, e}',
            ],
            [
                [1, 2, 'a', 'b', 3.14, 'hello', 'goodbye'],
                '{1, 2, a, b, 3.14, hello, goodbye}',
            ],
            [
                [1, 2, 3, new Set([1, 2]), 'a', 'b'],
                '{1, 2, 3, {1, 2}, a, b}',
            ],
            [
                ['a', 1, 'b', new Set([1, 'b']), new Set([3, 4, 5]), '4', 5],
                '{a, 1, b, {1, b}, {3, 4, 5}, 4, 5}',
            ],
            [
                [1, 2, new Set([1, 2, new Set([1, 2])])],
                '{1, 2, {1, 2, {1, 2}}}',
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
                $this->assertEquals(('{1, 2}'), $key);
                $this->assertEquals(new Set([1, 2]), $value);
            }
            if ($i === 2) {
                $this->assertEquals(('{3, 4}'), $key);
                $this->assertEquals(new Set([3, 4]), $value);
            }
            $i++;
        }
    }

    /**
     * @dataProvider dataProviderForCartesianProduct
     */
    public function testCartesianProduct(array $A, array $B, array $A×B, Set $R)
    {
        $setA      = new Set($A);
        $setB      = new Set($B);
        $setA×B    = $setA->cartesianProduct($setB);
        $A×B_array = $setA×B->asArray();

        $this->assertEquals($R, $setA×B);
        $this->assertEquals($A×B, $A×B_array);
        $this->assertEquals(count($setA×B), count($A×B));

        foreach ($setA×B as $key => $value) {
            $this->assertInstanceOf('Math\SetTheory\Set', $value);
            $this->assertEquals(2, count($value));
        }
        foreach ($A×B_array as $key => $value) {
            $this->assertInstanceOf('Math\SetTheory\Set', $value);
            $this->assertEquals(2, count($value));
        }
    }

    public function dataProviderForCartesianProduct()
    {
        return [
            [
                [1, 2],
                [3, 4],
                ['{1, 3}' => new Set([1, 3]), '{1, 4}' => new Set([1, 4]), '{2, 3}' => new Set([2, 3]), '{2, 4}' => new Set([2, 4])],
                new Set([new Set([1, 3]), new Set([1, 4]), new Set([2, 3]), new Set([2, 4])]),
            ],
            [
                [1, 2],
                ['red', 'white'],
                ['{1, red}' => new Set([1, 'red']), '{1, white}' => new Set([1, 'white']), '{2, red}' => new Set([2, 'red']), '{2, white}' => new Set([2, 'white'])],
                new Set([new Set([1, 'red']), new Set([1, 'white']), new Set([2, 'red']), new Set([2, 'white'])]),
            ],
            [
                [1, 2],
                [],
                [],
                new Set(),
            ],
        ];
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
          ->add([5, 6, 7])
          ->add(new Set([1, 2, 3]))
          ->remove([5, 6]);

        $B = new Set([3, 4, 7, new Set([1, 2, 3])]);

        $this->assertEquals($B, $A);
    }
}
