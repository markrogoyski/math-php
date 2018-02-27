<?php
namespace MathPHP\Tests\SetTheory;

use MathPHP\SetTheory\Set;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\LinearAlgebra\Matrix;

class SetOperationsTest extends \PHPUnit\Framework\TestCase
{
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
                new Set(),
                ['Ø' => new Set()],
            ],
            [
                [],
                1,
                [1 => 1],
            ],
            [
                [1, 2, 3],
                4,
                [1, 2, 3, 4],
            ],
            [
                [1, 2, 3],
                1,
                [1, 2, 3],
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
                [1, 2, 3, 'Set{4, 5}'],
            ],
            [
                [1, 2, 3],
                new Set([1, 2]),
                [1, 2, 3, 'Set{1, 2}'],
            ],
            [
                [1, 2, 3],
                -3,
                [1, 2, 3, -3],
            ],
            [
                [1, 2, 3],
                $vector,
                [1, 2, 3, $vector],
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
            if ($value instanceof \MathPHP\LinearAlgebra\Vector) {
                $objects++;
                $vector_key = get_class($value) . '(' . spl_object_hash($vector) . ')';
                $this->assertEquals($vector_key, $key);
                $this->assertEquals($vector, $value);
            }
            if ($value instanceof \MathPHP\LinearAlgebra\Matrix) {
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
            if ($value instanceof \MathPHP\LinearAlgebra\Vector) {
                $objects++;
                $this->assertInstanceOf(\MathPHP\LinearAlgebra\Vector::class, $value);
            }
            if ($value instanceof \MathPHP\LinearAlgebra\Matrix) {
                $objects++;
                $this->assertInstanceOf(\MathPHP\LinearAlgebra\Matrix::class, $value);
            }
            if ($value instanceof \StdClass) {
                $objects++;
                $this->assertInstanceOf(\StdClass::class, $value);
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
            if ($value instanceof \MathPHP\LinearAlgebra\Vector) {
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
    public function testAddMultiWithArrayOfArrays()
    {
        $set   = new Set([1, 2, 3]);
        $array = [4, 5, [1, 2, 3]];

        $set->addMulti($array);

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
    public function testAddMultiWithArrayOfArraysMultipleArraysAndDuplicates()
    {
        $set   = new Set([1, 2, 3]);
        $array = [4, 5, [1, 2, 3], [1, 2, 3], [5, 5, 5]];

        $set->addMulti($array);

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
     * @dataProvider dataProviderForAddMulti
     */
    public function testAddMulti(array $A, array $x, array $R)
    {
        $setA = new Set($A);
        $setR = new Set($R);

        $setA->addMulti($x);

        $this->assertEquals($setR, $setA);
        $this->assertEquals($setR->asArray(), $setA->asArray());
    }

    public function dataProviderForAddMulti()
    {
        $vector = new Vector([1, 2, 3]);

        return [
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
                [4],
                [1, 2, 3, 4],
            ],
            [
                [1, 2, 3],
                [4, 5],
                [1, 2, 3, 4, 5],
            ],
            [
                [1, 2, 3],
                [4, 5, 6],
                [1, 2, 3, 4, 5, 6],
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
                ['new', 4],
                [1, 2, 3, 'new', 4],
            ],
            [
                [1, 2, 3],
                [3.1, 4],
                [1, 2, 3, 3.1, 4],
            ],
            [
                [1, 2, 3],
                [new Set()],
                [1, 2, 3, 'Ø'],
            ],
            [
                [1, 2, 3],
                [new Set([4, 5])],
                [1, 2, 3, 'Set{4, 5}'],
            ],
            [
                [1, 2, 3],
                [new Set([1, 2]), 4],
                [1, 2, 3, 'Set{1, 2}', 4],
            ],
            [
                [1, 2, 3],
                [new Set([1, 2]), 6, 7, new Set([1, 2]), new Set([3, 4])],
                [1, 2, 3, 'Set{1, 2}', 6, 7, 'Set{3, 4}'],
            ],
            [
                [1, 2, 3],
                [-3],
                [1, 2, 3, -3],
            ],
            [
                [1, 2, 3],
                [4, $vector],
                [1, 2, 3, 4, $vector],
            ],
        ];
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
        $fh     = fopen(__FILE__, 'r');

        return [
            [
                [],
                null,
                [],
            ],
            [
                [null],
                null,
                [],
            ],
            [
                [1],
                1,
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
                [1, 2, 3, new Set([1, 2])],
                1,
                [2, 3, 'Set{1, 2}'],
            ],
            [
                [1, 2, 3, new Set([1, 2])],
                new Set([1, 2]),
                [1, 2, 3],
            ],
            [
                [1, 2, 3, new Set([1, 2])],
                'Set{1, 2}',
                [1, 2, 3],
            ],
            [
                [1, 2, 3, [1, 2, 3]],
                [1, 2, 3],
                [1, 2, 3],
            ],
            [
                [1, 2, 3, [1, 2, 3], [2, 3], [4, 5, 6]],
                [2, 3],
                [1, 2, 3, [1, 2, 3], [4, 5, 6]],
            ],
            [
                [1, 2, 3, [1, 2, 3], [2, 3], [4, 5, 6]],
                [4, 5, 6],
                [1, 2, 3, [1, 2, 3], [2, 3]],
            ],
            [
                [1, 2, 3, [1, 2, 3]],
                [6, 7, 3],
                [1, 2, 3, [1, 2, 3]],
            ],
            [
                [1, 2, 3, $vector],
                $vector,
                [1, 2, 3],
            ],
            [
                [1, 2, 3, $vector],
                [$vector], // Array containing vector
                [1, 2, 3, $vector],
            ],
            [
                [1, 2, 3, $vector],
                [1, $vector], // array containing 1 and vector
                [1, 2, 3, $vector],
            ],
            [
                [1, 2, 3, $fh],
                $fh,
                [1, 2, 3],
            ],
            [
                [1, 2, 3, $fh],
                [1, $fh], // array containing 1 and f1
                [1, 2, 3, $fh],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForRemoveMulti
     */
    public function testRemoveMulti(array $A, array $x, array $R)
    {
        $setA = new Set($A);
        $setR = new Set($R);

        $setA->removeMulti($x);

        $this->assertEquals($setR, $setA);
    }

    public function dataProviderForRemoveMulti()
    {
        $vector = new Vector([1, 2, 3]);
        $fh     = fopen(__FILE__, 'r');

        return [
            [
                [],
                [],
                [],
            ],
            [
                [],
                [null],
                [],
            ],
            [
                [null],
                [null],
                [],
            ],
            [
                [1],
                [1],
                [],
            ],
            [
                [1],
                [1],
                [],
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
                [4],
                [1, 2, 3],
            ],
            [
                [1, 2, 3],
                [5, 6],
                [1, 2, 3],
            ],
            [
                [1, 2, 3],
                [3, 4, 5],
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
                [5, 'a'],
                [1, 2, 3],
            ],
            [
                [1, 2, 3],
                [-1],
                [1, 2, 3],
            ],
            [
                [1, 2, 3, -3],
                [-3],
                [1, 2, 3],
            ],
            [
                [1, 2, 3, 4.5, 6.7],
                [4.5, 10],
                [1, 2, 3, 6.7],
            ],
            [
                [1, 2, 3, 'a', 'b', 'see'],
                ['b', 'z'],
                [1, 2, 3, 'a', 'see'],
            ],
            [
                [1, 2, 3, 'a', 'b', 'see'],
                ['b', 1, 'see', 5555],
                [ 2, 3, 'a'],
            ],
            [
                [1, 2, 3, new Set([1, 2])],
                [1],
                [2, 3, 'Set{1, 2}'],
            ],
            [
                [1, 2, 3, new Set([1, 2])],
                [new Set([1, 2])],
                [1, 2, 3],
            ],
            [
                [1, 2, 3, new Set([1, 2])],
                ['Set{1, 2}'],
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
                [$vector, 9],
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
            [
                [1, 2, 3, $fh],
                [1, $fh],
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
        $this->assertEquals(count($set), count($copy));
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
            $this->assertInstanceOf(Set::class, $value);
            $this->assertEquals(2, count($value));
        }
        foreach ($A×B_array as $key => $value) {
            $this->assertInstanceOf(Set::class, $value);
            $this->assertEquals(2, count($value));
        }
    }

    public function dataProviderForCartesianProduct()
    {
        return [
            [
                [1, 2],
                [3, 4],
                ['Set{1, 3}' => new Set([1, 3]), 'Set{1, 4}' => new Set([1, 4]), 'Set{2, 3}' => new Set([2, 3]), 'Set{2, 4}' => new Set([2, 4])],
                new Set([new Set([1, 3]), new Set([1, 4]), new Set([2, 3]), new Set([2, 4])]),
            ],
            [
                [1, 2],
                ['red', 'white'],
                ['Set{1, red}' => new Set([1, 'red']), 'Set{1, white}' => new Set([1, 'white']), 'Set{2, red}' => new Set([2, 'red']), 'Set{2, white}' => new Set([2, 'white'])],
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

    /**
     * @dataProvider dataProviderForPowerSet
     */
    public function testPowerSet(Set $A, Set $expected)
    {
        $P⟮S⟯ = $A->powerSet();

        $this->assertEquals($expected, $P⟮S⟯);
        $this->assertEquals($expected->asArray(), $P⟮S⟯->asArray());
        $this->assertEquals(count($expected), count($P⟮S⟯));
    }

    public function dataProviderForPowerSet()
    {
        return [
            // P({}) = {Ø}
            [
                new Set(),
                new Set([
                    new Set(),
                ]),
            ],
            // P({1}) = {{Ø}, {1}}
            [
                new Set([1]),
                new Set([
                    new Set(),
                    new Set([1]),
                ]),
            ],
            // P({1, 2, 3}) = {Ø, {1}, {2}, {3}, {1,2}, {1,3}, {2,3}, {1,2,3}}
            [
                new Set([1, 2, 3]),
                new Set([
                    new Set(),
                    new Set([1]),
                    new Set([2]),
                    new Set([3]),
                    new Set([1, 2]),
                    new Set([1, 3]),
                    new Set([2, 3]),
                    new Set([1, 2, 3]),
                ]),
            ],
            // P({x, y, z}) = {Ø, {x}, {y}, {z}, {x,y}, {x,z}, {y,z}, {x,y,z}}
            [
                new Set(['x', 'y', 'z']),
                new Set([
                    new Set(),
                    new Set(['x']),
                    new Set(['y']),
                    new Set(['z']),
                    new Set(['x', 'y']),
                    new Set(['x', 'z']),
                    new Set(['y', 'z']),
                    new Set(['x', 'y', 'z']),
                ]),
            ],
            // P({1, [1, 2]}) = {Ø, {1}, {[1, 2]}, {1, [1, 2]}}
            [
                new Set([1, [1, 2]]),
                new Set([
                    new Set(),
                    new Set([1]),
                    new Set([[1, 2]]),
                    new Set([1, [1, 2]]),
                ]),
            ],
        ];
    }

    public function dataProviderForSingleSet()
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
}
