<?php
namespace Math\SetTheory;

use Math\LinearAlgebra\Vector;
use Math\LinearAlgebra\Matrix;

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
        $this->assertEquals($setR->asArray(), $setA->asArray());
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
        $vector = new Vector([1, 2, 3]);

        return [
            [
                [],
                null,
                [null],
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
            [
                [1, 2, 3],
                $vector,
                [1, 2, 3, $vector],
            ],
            [
                [1, 2, 3],
                [4, $vector],
                [1, 2, 3, 4, $vector],
            ],
        ];
    }

    /**
     * When adding objects to a set, the key becomes to the objects hash.
     * The object is stored as is as the value.
     */
    public function testAddWithObjects()
    {
        $set    = new Set([1, 2, 3]);
        $vector = new Vector([1, 2, 3]);
        $matrix = new Matrix([[1,2,3],[2,3,4]]);

        $set->add($vector);
        $set->add($matrix);

        $this->assertEquals(5, count($set));
        $this->assertEquals(5, count($set->asArray()));

        $objects = 0;
        foreach ($set as $key => $value) {
            if ($value instanceof \Math\LinearAlgebra\Vector) {
                $objects++;
                $vector_key = get_class($value) . '(' . spl_object_hash($vector) . ')';
                $this->assertEquals($vector_key, $key);
                $this->assertEquals($vector, $value);
            }
            if ($value instanceof \Math\LinearAlgebra\Matrix) {
                $objects++;
                $matrix_key = get_class($value) . '(' . spl_object_hash($matrix) . ')';
                $this->assertEquals($matrix_key, $key);
                $this->assertEquals($matrix, $value);
            }
        }

        // There should have been two objects (vector and matrix)
        $this->assertEquals(2, $objects);
    }

    public function testAddWithMultipleObjects()
    {
        $set     = new Set([1, 2, 3]);
        $vector1 = new Vector([1, 2, 3]);
        $vector2 = new Vector([1, 2, 3]);
        $vector3 = new Vector([4, 5, 6]);
        $matrix  = new Matrix([[1,2,3],[2,3,4]]);
        $std1    = new \StdClass();
        $std2    = new \StdClass();
        $std3    = $std2; // Same object so this wont get added

        $set->add($vector1);
        $set->add($vector2);
        $set->add($vector3);
        $set->add($matrix);
        $set->add($std1);
        $set->add($std2);
        $set->add($std3);

        $this->assertEquals(9, count($set));
        $this->assertEquals(9, count($set->asArray()));

        $objects = 0;
        foreach ($set as $key => $value) {
            if ($value instanceof \Math\LinearAlgebra\Vector) {
                $objects++;
                $this->assertInstanceOf('Math\LinearAlgebra\Vector', $value);
            }
            if ($value instanceof \Math\LinearAlgebra\Matrix) {
                $objects++;
                $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $value);
            }
            if ($value instanceof \StdClass) {
                $objects++;
                $this->assertInstanceOf('StdClass', $value);
            }
        }

        // There should have been four objects (3 vectors and 1 matrix)
        $this->assertEquals(6, $objects);
    }

    public function testAddWithDuplicateObjects()
    {
        $set    = new Set([1, 2, 3]);
        $vector = new Vector([1, 2, 3]);

        // Add the same object twice.
        $set->add($vector);
        $set->add($vector);

        $this->assertEquals(4, count($set));
        $this->assertEquals(4, count($set->asArray()));

        $objects = 0;
        foreach ($set as $key => $value) {
            if ($value instanceof \Math\LinearAlgebra\Vector) {
                $objects++;
                $vector_key = get_class($value) . '(' . spl_object_hash($vector) . ')';
                $this->assertEquals($vector_key, $key);
                $this->assertEquals($vector, $value);
            }
        }

        // There should have only been one vector object.
        $this->assertEquals(1, $objects);
    }

    /**
     * In this case, we add an array that contains arrays.
     * So each array element will be added, but with the implementation
     * detail that they will be converted into ArrayObjects.
     */
    public function testAddWithArrayOfArrays()
    {
        $set   = new Set([1, 2, 3]);
        $array = [4, 5, [1, 2, 3]];

        $set->add($array);

        $this->assertEquals(6, count($set));
        $this->assertEquals(6, count($set->asArray()));

        $arrays = 0;
        foreach ($set as $key => $value) {
            if (is_array($value)) {
                $arrays++;
                $this->assertEquals([1, 2, 3], $value);
                $this->assertEquals(3, count($value));
                $this->assertEquals(1, $value[0]);
                $this->assertEquals(1, $value[0]);
                $this->assertEquals(1, $value[0]);
            }
        }

        // There should have only been one array.
        $this->assertEquals(1, $arrays);
    }

    /**
     * In this case, we add an array that contains arrays.
     * So each array element will be added, but with the implementation
     * detail that they will be converted into ArrayObjects.
     */
    public function testAddWithArrayOfArraysMultipleArraysAndDuplicates()
    {
        $set   = new Set([1, 2, 3]);
        $array = [4, 5, [1, 2, 3], [1, 2, 3], [5, 5, 5]];

        $set->add($array);

        // Only 7, because [1, 2, 3] was in there twice.
        $this->assertEquals(7, count($set));
        $this->assertEquals(7, count($set->asArray()));

        $arrays = 0;
        foreach ($set as $key => $value) {
            if (is_array($value)) {
                $arrays++;
                $this->assertEquals(3, count($value));
            }
        }

        // There should have been 2 arrays.
        $this->assertEquals(2, $arrays);
    }

    /**
     * When adding resources to a set, the key becomes to the resource ID.
     * The resource is stored as is as the value.
     */
    public function testAddWithResources()
    {
        $set = new Set();
        $fh  = fopen(__FILE__, 'r');
        $set->add($fh);
        $set->add($fh); // Should only get added once

        $this->assertEquals(1, count($set));
        $this->assertEquals(1, count($set->asArray()));

        $resources = 0;
        foreach ($set as $key => $value) {
            if (is_resource($value)) {
                $resources++;
                $vector_key = 'Resource(' . strval($value) . ')';
                $this->assertEquals($vector_key, $key);
                $this->assertEquals($fh, $value);
            }
        }

        // There should have been one resource
        $this->assertEquals(1, $resources);
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
        $vector = new Vector([1, 2, 3]);

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
            [
                [1, 2, 3, [1, 2, 3]],
                [1, 2, 3],
                [[1, 2, 3]],
            ],
            [
                [1, 2, 3, [1, 2, 3], [2, 3], [4, 5, 6]],
                [2, 3],
                [1, [1, 2, 3], [2, 3], [4, 5, 6]],
            ],
            [
                [1, 2, 3, [1, 2, 3], [2, 3], [4, 5, 6]],
                [4, 5, 6],
                [1, 2, 3, [1, 2, 3], [2, 3], [4, 5, 6]],
            ],
            [
                [1, 2, 3, [1, 2, 3]],
                [[1, 2, 3]],
                [1, 2, 3],
            ],
            [
                [1, 2, 3, [1, 2, 3], [2, 3], [4, 5, 6]],
                [[2, 3]],
                [1, 2, 3, [1, 2, 3], [4, 5, 6]],
            ],
            [
                [1, 2, 3, [1, 2, 3], [2, 3], [4, 5, 6]],
                [[4, 5, 6]],
                [1, 2, 3, [1, 2, 3], [2, 3]],
            ],
            [
                [1, 2, 3, [1, 2, 3], [2, 3], [4, 5, 6]],
                [[1, 2, 3], [4, 5, 6]],
                [1, 2, 3, [2, 3]],
            ],
            [
                [1, 2, 3, [1, 2, 3], [2, 3], [4, 5, 6]],
                [1, [4, 5, 6], 3],
                [2, [1, 2, 3], [2, 3]],
            ],
            [
                [1, 2, 3, $vector],
                $vector,
                [1, 2, 3],
            ],
            [
                [1, 2, 3, $vector],
                [$vector],
                [1, 2, 3],
            ],
            [
                [1, 2, 3, $vector],
                [1, $vector],
                [2, 3],
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

    public function testUnionWithArrays()
    {
        $A        = new Set([1, 2, [1, 2, 3]]);
        $B        = new Set([2, 3, [2, 3, 4]]);
        $A∪B      = $A->union($B);
        $expected = new Set([1, 2, [1, 2, 3], 3, [2, 3, 4]]);

        $this->assertEquals($expected, $A∪B);
        $this->assertEquals($expected->asArray(), $A∪B->asArray());

        $A        = new Set([1, 2, [1, 2, 3]]);
        $B        = new Set([2, 3, [2, 3, 4], [1, 2, 3]]);
        $A∪B      = $A->union($B);
        $expected = new Set([1, 2, [1, 2, 3], 3, [2, 3, 4]]);

        $this->assertEquals($expected, $A∪B);
        $this->assertEquals($expected->asArray(), $A∪B->asArray());
    }

    public function testUnionWithObjects()
    {
        $vector1 = new Vector([1, 2, 3]);
        $vector2 = new Vector([1, 2, 3]);

        $A        = new Set([1, 2, $vector1]);
        $B        = new Set([2, 3, $vector2]);
        $A∪B      = $A->union($B);
        $expected = new Set([1, 2, $vector1, 3, $vector2]);

        $this->assertEquals($expected, $A∪B);
        $this->assertEquals($expected->asArray(), $A∪B->asArray());

        $A        = new Set([1, 2, $vector1]);
        $B        = new Set([2, 3, $vector2, $vector1]);
        $A∪B      = $A->union($B);
        $expected = new Set([1, 2, $vector1, 3, $vector2]);

        $this->assertEquals($expected, $A∪B);
        $this->assertEquals($expected->asArray(), $A∪B->asArray());
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

    public function testIntersectWithArrays()
    {
        $A        = new Set([1, 2, [1, 2, 3]]);
        $B        = new Set([2, 3, [2, 3, 4]]);
        $A∩B      = $A->intersect($B);
        $expected = new Set([2]);

        $this->assertEquals($expected, $A∩B);
        $this->assertEquals($expected->asArray(), $A∩B->asArray());

        $A        = new Set([1, 2, [1, 2, 3]]);
        $B        = new Set([2, 3, [2, 3, 4], [1, 2, 3]]);
        $A∩B      = $A->intersect($B);
        $expected = new Set([2, [1, 2, 3]]);

        $this->assertEquals($expected, $A∩B);
        $this->assertEquals($expected->asArray(), $A∩B->asArray());
    }

    public function testIntersectWithObjects()
    {
        $vector1 = new Vector([1, 2, 3]);
        $vector2 = new Vector([1, 2, 3]);

        $A        = new Set([1, 2, $vector1]);
        $B        = new Set([2, 3, $vector2]);
        $A∩B      = $A->intersect($B);
        $expected = new Set([2]);

        $this->assertEquals($expected, $A∩B);
        $this->assertEquals($expected->asArray(), $A∩B->asArray());

        $A        = new Set([1, 2, $vector1]);
        $B        = new Set([2, 3, $vector2, $vector1]);
        $A∩B      = $A->intersect($B);
        $expected = new Set([2, $vector1]);

        $this->assertEquals($expected, $A∩B);
        $this->assertEquals($expected->asArray(), $A∩B->asArray());
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

    public function testDifferenceWithArrays()
    {
        $A        = new Set([1, 2, [1, 2, 3]]);
        $B        = new Set([2, 3, [2, 3, 4]]);
        $A∖B      = $A->difference($B);
        $expected = new Set([1, [1, 2, 3]]);

        $this->assertEquals($expected, $A∖B);
        $this->assertEquals($expected->asArray(), $A∖B->asArray());

        $A        = new Set([1, 2, [1, 2, 3]]);
        $B        = new Set([2, 3, [2, 3, 4], [1, 2, 3]]);
        $A∖B      = $A->difference($B);
        $expected = new Set([1]);

        $this->assertEquals($expected, $A∖B);
        $this->assertEquals($expected->asArray(), $A∖B->asArray());
    }

    public function testDifferenceWithObjects()
    {
        $vector1 = new Vector([1, 2, 3]);
        $vector2 = new Vector([1, 2, 3]);

        $A        = new Set([1, 2, $vector1]);
        $B        = new Set([2, 3, $vector2]);
        $A∖B      = $A->difference($B);
        $expected = new Set([1, $vector1]);

        $this->assertEquals($expected, $A∖B);
        $this->assertEquals($expected->asArray(), $A∖B->asArray());

        $A        = new Set([1, 2, $vector1]);
        $B        = new Set([2, 3, $vector2, $vector1]);
        $A∖B      = $A->difference($B);
        $expected = new Set([1]);

        $this->assertEquals($expected, $A∖B);
        $this->assertEquals($expected->asArray(), $A∖B->asArray());
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

    public function testSymmetricDifferenceWithArrays()
    {
        $A        = new Set([1, 2, [1, 2, 3]]);
        $B        = new Set([2, 3, [2, 3, 4]]);
        $AΔB      = $A->symmetricDifference($B);
        $expected = new Set([1, 3, [1, 2, 3], [2, 3, 4]]);

        $this->assertEquals($expected, $AΔB);
        $this->assertEquals($expected->asArray(), $AΔB->asArray());

        $A        = new Set([1, 2, [1, 2, 3]]);
        $B        = new Set([2, 3, [2, 3, 4], [1, 2, 3]]);
        $AΔB      = $A->symmetricDifference($B);
        $expected = new Set([1, 3, [2, 3, 4]]);

        $this->assertEquals($expected, $AΔB);
        $this->assertEquals($expected->asArray(), $AΔB->asArray());
    }

    public function testSymmetricDifferenceWithObjects()
    {
        $vector1 = new Vector([1, 2, 3]);
        $vector2 = new Vector([1, 2, 3]);

        $A        = new Set([1, 2, $vector1]);
        $B        = new Set([2, 3, $vector2]);
        $AΔB      = $A->symmetricDifference($B);
        $expected = new Set([1, 3, $vector1, $vector2]);

        $this->assertEquals($expected, $AΔB);
        $this->assertEquals($expected->asArray(), $AΔB->asArray());

        $A        = new Set([1, 2, $vector1]);
        $B        = new Set([2, 3, $vector2, $vector1]);
        $AΔB      = $A->symmetricDifference($B);
        $expected = new Set([1, 3, $vector2]);

        $this->assertEquals($expected, $AΔB);
        $this->assertEquals($expected->asArray(), $AΔB->asArray());
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
        $vector      = new Vector([1, 2, 3]);
        $vector_hash = spl_object_hash($vector);

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
            [
                [1, 2, [1, 2, 3]],
                '{1, 2, Array(a:3:{i:0;i:1;i:1;i:2;i:2;i:3;})}',
            ],
            [
                [1, 2, [1, 2, 3], [1, 2, 3]],
                '{1, 2, Array(a:3:{i:0;i:1;i:1;i:2;i:2;i:3;})}',
            ],
            [
                [1, 2, $vector],
                "{1, 2, Math\LinearAlgebra\Vector($vector_hash)}",
            ],
            [
                [1, 2, $vector, $vector],
                "{1, 2, Math\LinearAlgebra\Vector($vector_hash)}",
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
