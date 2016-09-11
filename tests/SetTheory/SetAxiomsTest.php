<?php
namespace Math\SetTheory;

/**
 * Tests of Set axioms
 * These tests don't test specific functions,
 * but rather set axioms which in term make use of multiple functions.
 * If all the set logic is implemented properly, these tests should
 * all work out according to the axioms.
 *
 * Axioms tested:
 *  - Subsets
 *    - Ø ⊆ A
 *    - A ⊆ A
 *    - A = B iff A ⊆ B and B ⊆ A
 *  - Union
 *    - A ∪ B = B ∪ A
 *    - A ∪ (B ∪ C) = (A ∪ B) ∪ C
 *    - A ⊆ (A ∪ B)
 *    - A ∪ A = A
 *    - A ∪ Ø = A
 *  - Intersection
 *    - A ∩ B = B ∩ A
 *    - A ∩ (B ∩ C) = (A ∩ B) ∩ C
 *    - (A ∩ B) ⊆ A
 *    - A ∩ A = A
 *    - A ∩ Ø = Ø
 *  - Complement (difference)
 *    - A ∖ B ≠ B ∖ A for A ≠ B
 *    - A ∖ A = Ø
 *  - Symmetric difference
 *    - A Δ B = (A ∖ B) ∪ (B ∖ A)
 *  - Cartesian product
 *    - A × Ø = Ø
 *    - A × (B ∪ C) = (A × B) ∪ (A × C)
 *    - (A ∪ B) × C = (A × C) ∪ (B × C)
 */
class SetAxiomsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Axiom: Ø ⊆ A
     * The empty set is a subset of every set
     * @dataProvider dataProviderForSingleSet
     */
    public function testEmptySetSubsetOfEverySet(Set $A)
    {
        $Ø = new Set();

        $this->assertTrue($Ø->isSubset($A));
    }

    /**
     * Axiom: A ⊆ A
     * Every set is a subset of itself
     * @dataProvider dataProviderForSingleSet
     */
    public function testSetIsSubsetOfItself(Set $A)
    {
        $this->assertTrue($A->isSubset($A));
    }


    /**
     * Axiom: A = B iff A ⊆ B and B ⊆ A
     * Sets are equal if and only if they are both subsets of each other.
     * @dataProvider dataProviderForSingleSet
     */
    public function testEqualSetsAreSubsetsInBothDirections(Set $A)
    {
        $this->assertEquals($A, $A);
        $this->assertTrue($A->isSubset($A));
        $this->assertTRue($A->isSubset($A));
    }

    public function dataProviderForSingleSet()
    {
        return [
            [new Set([])],
            [new Set([0])],
            [new Set([1])],
            [new Set([5])],
            [new Set([-5])],
            [new Set([1, 2])],
            [new Set([1, 2, 3])],
            [new Set([1, -2, 3])],
            [new Set([1, 2, 3, 4, 5, 6])],
            [new Set([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])],
            [new Set([1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 2, 2.01, 2.001, 2.15])],
            [new Set(['a'])],
            [new Set(['a', 'b'])],
            [new Set(['a', 'b', 'c', 'd', 'e'])],
            [new Set([1, 2, 'a', 'b', 3.14, 'hello', 'goodbye'])],
            [new Set([1, 2, 3, new Set([1, 2]), 'a', 'b'])],
            [new Set(['a', 1, 'b', new Set([1, 'b']), new Set([3, 4, 5]), '4', 5])],
            [new Set(['a', 1, 'b', new Set([1, 'b']), new Set([3, 4, 5]), '4', 5, new Set([3, 4, 5, new Set([1, 2])])])],
        ];
    }

    /**
     * Axiom: A ∪ B = B ∪ A
     * Union of A and B is equal to union of B and A
     * @dataProvider dataProviderForTwoSets
     */
    public function testUnionAAndBEqualToUnionBAndA(Set $A, Set $B)
    {
        $A∪B = $A->union($B);
        $B∪A = $B->union($A);

        $this->assertEquals($A∪B, $B∪A);
        $this->assertEquals($A∪B->asArray(), $B∪A->asArray());
    }

    public function dataProviderForTwoSets()
    {
        return [
            [
                new Set([]),
                new Set([]),
            ],
            [
                new Set([1]),
                new Set([]),
            ],
            [
                new Set([]),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([2]),
            ],
            [
                new Set([2]),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([2]),
            ],
            [
                new Set([2]),
                new Set([1]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b']),
                new Set([1, 'a', 'k']),
            ],
            [
                new Set([1, 2, 3, 'a', 'b', new Set([1, 2])]),
                new Set([1, 'a', 'k']),
            ],
            [
                new Set([1, 2, 3, 'a', 'b']),
                new Set([1, 'a', 'k', new Set([1, 2])]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b', new Set()]),
                new Set([1, 'a', 'k', new Set([1, 2])]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b', new Set([1, 2])]),
                new Set([1, 'a', 'k', -2, '2.4', 3.5, new Set([1, 2])]),
            ],
        ];
    }

    /**
     * Axiom: A ∪ (B ∪ C) = (A ∪ B) ∪ C
     * A union B and C equals A and B union C
     * @dataProvider dataProviderForThreeSets
     */
    public function testAUnionBCEqualsABUnionC(Set $A, Set $B, Set $C)
    {
        $A∪⟮B∪C⟯ = $A->union($B->union($C));
        $⟮A∪B⟯∪C = $A->union($B)->union($C);

        $this->assertEquals($A∪⟮B∪C⟯, $⟮A∪B⟯∪C);
        $this->assertEquals($A∪⟮B∪C⟯->asArray(), $⟮A∪B⟯∪C->asArray());
    }

    /**
     * Axiom: A ⊆ (A ∪ B)
     * A is a subset of A union B
     * @dataProvider dataProviderForTwoSets
     */
    public function testAIsSubsetOfAUnionB(Set $A, Set $B)
    {
        $A∪B = $A->union($B);

        $this->assertTrue($A->isSubset($A∪B));
        $this->assertTrue($B->isSubset($A∪B));
    }

    /**
     * Axiom: A ∪ A = A
     * A union A equals A
     * @dataProvider dataProviderForSingleSet
     */
    public function testAUnionAEqualsA(Set $A)
    {
        $A∪A = $A->union($A);

        $this->assertEquals($A, $A∪A);
        $this->assertEquals($A->asArray(), $A∪A->asArray());
    }

    /**
     * Axiom: A ∪ Ø = A
     * A union empty set is A
     * @dataProvider dataProviderForSingleSet
     */
    public function testAUnionEmptySetEqualsA(Set $A)
    {
        $Ø   = new Set();
        $A∪Ø = $A->union($Ø);

        $this->assertEquals($A, $A∪Ø);
        $this->assertEquals($A->asArray(), $A∪Ø->asArray());
    }

    public function dataProviderForThreeSets()
    {
        return [
            [
                new Set([]),
                new Set([]),
                new Set([]),
            ],
            [
                new Set([1]),
                new Set([]),
                new Set([]),
            ],
            [
                new Set([]),
                new Set([]),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([1]),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([2]),
                new Set([2]),
            ],
            [
                new Set([2]),
                new Set([1]),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([2]),
                new Set([3]),
            ],
            [
                new Set([2]),
                new Set([1]),
                new Set([1, 4]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b']),
                new Set([1, 'a', 'k']),
                new Set([1, 9]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b', new Set([1, 2])]),
                new Set([1, 'a', 'k']),
                new Set([34, 40]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b']),
                new Set([1, 'a', 'k', new Set([1, 2])]),
                new Set([1, 9, 33]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b', new Set()]),
                new Set([1, 'a', 'k', new Set([1, 2])]),
                new Set([1, new Set([1, 2])]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b', new Set([1, 2])]),
                new Set([1, 'a', 'k', -2, '2.4', 3.5, new Set([1, 2])]),
                new Set([1, new Set([1, 2])], 99),
            ],
        ];
    }

    /**
     * Axiom: A ∩ B = B ∩ A
     * Inersection of A and B is equal to intersection of B and A
     * @dataProvider dataProviderForTwoSets
     */
    public function testIntersectionAAndBEqualToIntersectionBAndA(Set $A, Set $B)
    {
        $A∩B = $A->intersect($B);
        $B∩A = $B->intersect($A);

        $this->assertEquals($A∩B, $B∩A);
        $this->assertEquals($A∩B->asArray(), $B∩A->asArray());
    }

    /**
     * Axiom: A ∩ (B ∩ C) = (A ∩ B) ∩ C
     * A intersection B and C equals A and B intersection C
     * @dataProvider dataProviderForThreeSets
     */
    public function testAIntersectionBCEqualsABIntersectionC(Set $A, Set $B, Set $C)
    {
        $A∩⟮B∩C⟯ = $A->intersect($B->intersect($C));
        $⟮A∩B⟯∩C = $A->intersect($B)->intersect($C);

        $this->assertEquals($A∩⟮B∩C⟯, $⟮A∩B⟯∩C);
        $this->assertEquals($A∩⟮B∩C⟯->asArray(), $⟮A∩B⟯∩C->asArray());
    }

    /**
     * Axiom: (A ∩ B) ⊆ A
     * A intersect B is a subset of A
     * @dataProvider dataProviderForTwoSets
     */
    public function testAIntersectionBIsSubsetOfA(Set $A, Set $B)
    {
        $A∩B = $A->intersect($B);

        $this->assertTrue($A∩B->isSubset($A));
        $this->assertTrue($A∩B->isSubset($B));
    }

    /**
     * Axiom: A ∩ A = A
     * A intersection A equals A
     * @dataProvider dataProviderForSingleSet
     */
    public function testAIntersectionAEqualsA(Set $A)
    {
        $A∩A = $A->intersect($A);

        $this->assertEquals($A, $A∩A);
        $this->assertEquals($A->asArray(), $A∩A->asArray());
    }

    /**
     * Axiom: A ∩ Ø = Ø
     * A union empty set is A
     * @dataProvider dataProviderForSingleSet
     */
    public function testAIntersectionEmptySetIsEmptySet(Set $A)
    {
        $Ø   = new Set();
        $A∩Ø = $A->intersect($Ø);

        $this->assertEquals($Ø, $A∩Ø);
        $this->assertEquals($Ø->asArray(), $A∩Ø->asArray());
    }

    /**
     * Axiom: A ∖ B ≠ B ∖ A for A ≠ B
     * A diff B does not equal B diff A if A and B are different sets
     * @dataProvider dataProviderForTwoSetsDifferent
     */
    public function testADiffBDifferentFromBDiffAWhenNotEqual(Set $A, Set $B)
    {
        $A∖B = $A->difference($B);
        $B∖A = $B->difference($A);

        $this->assertNotEquals($A∖B, $B∖A);
        $this->assertNotEquals($A∖B->asArray(), $B∖A->asArray());
    }

    public function dataProviderForTwoSetsDifferent()
    {
        return [
            [
                new Set([1]),
                new Set([]),
            ],
            [
                new Set([]),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([2]),
            ],
            [
                new Set([2]),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([2]),
            ],
            [
                new Set([2]),
                new Set([1]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b']),
                new Set([1, 'a', 'k']),
            ],
            [
                new Set([1, 2, 3, 'a', 'b', new Set([1, 2])]),
                new Set([1, 'a', 'k']),
            ],
            [
                new Set([1, 2, 3, 'a', 'b']),
                new Set([1, 'a', 'k', new Set([1, 2])]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b', new Set()]),
                new Set([1, 'a', 'k', new Set([1, 2])]),
            ],
            [
                new Set([1, 2, 3, 'a', 'b', new Set([1, 2])]),
                new Set([1, 'a', 'k', -2, '2.4', 3.5, new Set([1, 2])]),
            ],
        ];
    }

    /**
     * Axiom: A ∖ A = Ø
     * A diff itself is the empty set
     * @dataProvider dataProviderForSingleSet
     */
    public function testADiffItselfIsEmptySet(Set $A)
    {
        $Ø   = new Set();
        $A∖A = $A->difference($A);

        $this->assertEquals($Ø, $A∖A);
        $this->assertEquals($Ø->asArray(), $A∖A->asArray());
    }

    /**
     * Axiom: A Δ B = (A ∖ B) ∪ (B ∖ A)
     * A symmetric different B equals union of A diff B and B diff A
     * @dataProvider dataProviderForTwoSets
     */
    public function testASymmetricDifferentBEqualsUnionADiffBAndBDiffA(Set $A, Set $B)
    {
        $AΔB       = $A->symmetricDifference($B);
        $A∖B       = $A->difference($B);
        $B∖A       = $B->difference($A);
        $⟮A∖B⟯∪⟮B∖A⟯ = $A∖B->union($B∖A);

        $this->assertEquals($AΔB, $⟮A∖B⟯∪⟮B∖A⟯);
        $this->assertEquals($AΔB->asArray(), $⟮A∖B⟯∪⟮B∖A⟯->asArray());
    }

    /**
     * Axiom: A × Ø = Ø
     * A cartesian product with empty set is the empty set
     * @dataProvider dataProviderForSingleSet
     */
    public function testACartesianProductWithEmptySetIsEmptySet(Set $A)
    {
        $Ø   = new Set();
        $A×Ø = $A->cartesianProduct($Ø);

        $this->assertEquals($Ø, $A×Ø);
    }

    /**
     * Axiom: A × (B ∪ C) = (A × B) ∪ (A × C)
     * A cross union of B and C is the union of A cross B and A cross C
     * @dataProvider dataProviderForThreeSets
     */
    public function testACrossUnsionBCEqualsACrossBUnionACrossC(Set $A, Set $B, Set $C)
    {
        $A×⟮B∪C⟯ = $A->cartesianProduct($B->union($C));
        $⟮A×B⟯∪⟮A×C⟯ = $A->cartesianProduct($B)->union($A->cartesianProduct($C));

        $this->assertEquals($A×⟮B∪C⟯, $⟮A×B⟯∪⟮A×C⟯);
        $this->assertEquals($A×⟮B∪C⟯->asArray(), $⟮A×B⟯∪⟮A×C⟯->asArray());
    }

    /**
     * Axiom: (A ∪ B) × C = (A × C) ∪ (B × C)
     * A union B cross C is the union of A cross C and B cross C
     * @dataProvider dataProviderForThreeSets
     */
    public function testAUnsionBCrossCEqualsUnsionOfACRossCAndBCrossC(Set $A, Set $B, Set $C)
    {
        $⟮A∪B⟯×C = $A->union($B)->cartesianProduct($C);
        $⟮A×C⟯∪⟮B×C⟯ = $A->cartesianProduct($C)->union($B->cartesianProduct($C));

        $this->assertEquals($⟮A∪B⟯×C, $⟮A×C⟯∪⟮B×C⟯);
        $this->assertEquals($⟮A∪B⟯×C->asArray(), $⟮A×C⟯∪⟮B×C⟯->asArray());
    }
}
