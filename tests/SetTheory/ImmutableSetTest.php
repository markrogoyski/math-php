<?php
namespace MathPHP\Tests\SetTheory;

use MathPHP\SetTheory\ImmutableSet;
use MathPHP\SetTheory\Set;

class ImmutableSetTest extends \PHPUnit\Framework\TestCase
{
    public function testAddDoesNothing()
    {
        $A = new ImmutableSet([1, 2, 3, 4]);
        $B = $A->copy();

        $A->add(5);
        $A->add(6);
        $A->add([7, 8, 9]);
        $A->add(new Set(['a', 'b']));

        $this->assertEquals($B, $A);
        $this->assertEquals($B->asArray(), $A->asArray());
    }

    public function testAddMultiDoesNothing()
    {
        $A = new ImmutableSet([1, 2, 3, 4]);
        $B = $A->copy();

        $A->addMulti([5]);
        $A->addMulti([6, 7, 8]);
        $A->addMulti([7, 8, 9]);
        $A->addMulti([new Set(['a', 'b'])]);

        $this->assertEquals($B, $A);
        $this->assertEquals($B->asArray(), $A->asArray());
    }

    public function testRemoveDoesNothing()
    {
        $A = new ImmutableSet([1, 2, 3, 4]);
        $B = $A->copy();

        $A->remove(1);
        $A->remove([2, 3]);

        $this->assertEquals($B, $A);
        $this->assertEquals($B->asArray(), $A->asArray());
    }

    public function testRemoveMultiDoesNothing()
    {
        $A = new ImmutableSet([1, 2, 3, 4]);
        $B = $A->copy();

        $A->removeMulti([1]);
        $A->removeMulti([2, 3]);

        $this->assertEquals($B, $A);
        $this->assertEquals($B->asArray(), $A->asArray());
    }

    public function testClearDoesNothing()
    {
        $A = new ImmutableSet([1, 2, 3, 4]);
        $B = $A->copy();

        $A->clear();

        $this->assertEquals($B, $A);
        $this->assertEquals($B->asArray(), $A->asArray());
    }

    public function testIsASet()
    {
        $A = new ImmutableSet([1, 2, 3, 4]);

        $this->assertInstanceOf(Set::class, $A);
    }

    public function testActsLikeASet()
    {
        $A = new ImmutableSet([1, 2, 3]);
        $B = new ImmutableSet([3, 4, 5]);

        $this->assertEquals(3, $A->length());
        $this->assertEquals(3, $B->length());

        $this->assertFalse($A->isEmpty());
        $this->assertFalse($B->isEmpty());

        $this->assertTrue($A->isMember(1));
        $this->assertTrue($A->isMember(2));
        $this->assertTrue($A->isMember(3));
        $this->assertTrue($B->isMember(3));
        $this->assertTrue($B->isMember(4));
        $this->assertTrue($B->isMember(5));

        $this->assertTrue($A->isNotMember(10));
        $this->assertTrue($B->isNotMember(10));

        $this->assertFalse($A->isDisjoint($B));
        $this->assertFalse($A->isSubset($B));
        $this->assertFalse($A->isProperSubset($B));
        $this->assertFalse($A->isSuperset($B));
        $this->assertFalse($A->isProperSuperset($B));

        $this->assertEquals(new Set([1, 2, 3, 4, 5]), $A->union($B));
        $this->assertEquals(new Set([3]), $A->intersect($B));
        $this->assertEquals(new Set([1, 2]), $A->difference($B));
        $this->assertEquals(new Set([1, 2, 4, 5]), $A->symmetricDifference($B));
        $this->assertEquals(new Set([
            new Set([1, 3]),
            new Set([1, 4]),
            new Set([1, 5]),
            new Set([2, 3]),
            new Set([2, 4]),
            new Set([2, 5]),
            new Set([3, 3]),
            new Set([3, 4]),
            new Set([3, 5]),
        ]), $A->cartesianProduct($B));

        $this->assertEquals($A, $A->copy());
        $this->assertEquals('Set{1, 2, 3}', strval($A));

        $this->assertEquals(3, count($A));
        $this->assertEquals(3, count($B));

        $i = 0;
        foreach ($A as $x) {
            $i++;
        }
        $this->assertEquals(3, $i);
    }
}
