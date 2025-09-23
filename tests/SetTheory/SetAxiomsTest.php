<?php

namespace MathPHP\Tests\SetTheory;

use MathPHP\SetTheory\Set;

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
 *    - A ∪ (B ∩ C) = (A ∪ B) ∩ (A ∪ C)
 *    - A ∪ (A ∩ B) = A
 *    - A ⊆ (A ∪ B)
 *    - A ∪ A = A
 *    - A ∪ Ø = A
 *    - |A ∪ B| = |A| + |B| - |A ∩ B|
 *  - Intersection
 *    - A ∩ B = B ∩ A
 *    - A ∩ (B ∩ C) = (A ∩ B) ∩ C
 *    - A ∩ (B ∪ C) = (A ∩ B) ∪ (A ∩ C)
 *    - A ∩ (A ∪ B) = A
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
 *    - |A × B| = |A| * |B|
 *  - Power set
 *    - |S| = n, then |P(S)| = 2ⁿ
 */
class SetAxiomsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test Axiom: Ø ⊆ A
     * The empty set is a subset of every set
     * @dataProvider dataProviderForSingleSet
     */
    public function testEmptySetSubsetOfEverySet(Set $A)
    {
        // Given
        $Ø = new Set();

        // When
        $isSubset = $Ø->isSubset($A);

        // Then
        $this->assertTrue($isSubset);
    }

    /**
     * @test Axiom: A ⊆ A
     * Every set is a subset of itself
     * @dataProvider dataProviderForSingleSet
     */
    public function testSetIsSubsetOfItself(Set $A)
    {
        // When
        $isSubset = $A->isSubset($A);

        // Then
        $this->assertTrue($isSubset);
    }


    /**
     * @test Axiom: A = B iff A ⊆ B and B ⊆ A
     * Sets are equal if and only if they are both subsets of each other.
     * @dataProvider dataProviderForSingleSet
     */
    public function testEqualSetsAreSubsetsInBothDirections(Set $A)
    {
        // Given
        $B = $A;
        $this->assertEquals($A, $A);

        // Then
        $this->assertTrue($A->isSubset($B));
        $this->assertTrue($B->isSubset($A));
    }

    public function dataProviderForSingleSet(): array
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
     * @test Axiom: A ∪ B = B ∪ A
     * Union is commutative
     *
     * @dataProvider dataProviderForTwoSets
     * @param        Set $A
     * @param        Set $B
     */
    public function testUnionCommutative(Set $A, Set $B)
    {
        // Given
        $A∪B = $A->union($B);
        $B∪A = $B->union($A);

        // Then
        $this->assertEquals($A∪B, $B∪A);
        $this->assertEquals($A∪B->asArray(), $B∪A->asArray());
    }

    public function dataProviderForTwoSets(): array
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
     * @test Axiom: A ∪ (B ∪ C) = (A ∪ B) ∪ C
     * Unsion is associative
     *
     * @dataProvider dataProviderForThreeSets
     * @param        Set $A
     * @param        Set $B
     * @param        Set $C
     */
    public function testUnsionAssociative(Set $A, Set $B, Set $C)
    {
        // Given
        $A∪⟮B∪C⟯ = $A->union($B->union($C));
        $⟮A∪B⟯∪C = $A->union($B)->union($C);

        // Then
        $this->assertEquals($A∪⟮B∪C⟯, $⟮A∪B⟯∪C);
        $this->assertEquals($A∪⟮B∪C⟯->asArray(), $⟮A∪B⟯∪C->asArray());
    }

    /**
     * @test Axiom: A ∪ (B ∩ C) = (A ∪ B) ∩ (A ∪ C)
     * Union is distributive
     *
     * @dataProvider dataProviderForThreeSets
     * @param        Set $A
     * @param        Set $B
     * @param        Set $C
     */
    public function testUnionDistributive(Set $A, Set $B, Set $C)
    {
        // Given
        $A∪⟮B∩C⟯    = $A->union($B->intersect($C));
        $⟮A∪B⟯∩⟮A∪C⟯ = $A->union($B)->intersect($A->union($C));

        // Then
        $this->assertEquals($A∪⟮B∩C⟯, $⟮A∪B⟯∩⟮A∪C⟯);
        $this->assertEquals($A∪⟮B∩C⟯->asArray(), $⟮A∪B⟯∩⟮A∪C⟯->asArray());
    }

    /**
     * @test Axiom: A ∪ (A ∩ B) = A
     * Union absorbtion law
     *
     * @dataProvider dataProviderForTwoSets
     * @param        Set $A
     * @param        Set $B
     */
    public function testUnionAbsorbtion(Set $A, Set $B)
    {
        // Given
        $A∪⟮B∩C⟯ = $A->union($A->intersect($B));

        // Then
        $this->assertEquals($A, $A∪⟮B∩C⟯);
        $this->assertEquals($A->asArray(), $A∪⟮B∩C⟯->asArray());
    }

    /**
     * @test Axiom: A ⊆ (A ∪ B)
     * A is a subset of A union B
     *
     * @dataProvider dataProviderForTwoSets
     * @param        Set $A
     * @param        Set $B
     */
    public function testAIsSubsetOfAUnionB(Set $A, Set $B)
    {
        // Given
        $A∪B = $A->union($B);

        // Then
        $this->assertTrue($A->isSubset($A∪B));
        $this->assertTrue($B->isSubset($A∪B));
    }

    /**
     * @test Axiom: A ∪ A = A
     * A union A equals A
     *
     * @dataProvider dataProviderForSingleSet
     * @param       Set $A
     */
    public function testAUnionAEqualsA(Set $A)
    {
        // Given
        $A∪A = $A->union($A);

        // Then
        $this->assertEquals($A, $A∪A);
        $this->assertEquals($A->asArray(), $A∪A->asArray());
    }

    /**
     * @test Axiom: A ∪ Ø = A
     * A union empty set is A
     *
     * @dataProvider dataProviderForSingleSet
     * @param        Set $A
     */
    public function testAUnionEmptySetEqualsA(Set $A)
    {
        // Given
        $Ø   = new Set();
        $A∪Ø = $A->union($Ø);

        // Then
        $this->assertEquals($A, $A∪Ø);
        $this->assertEquals($A->asArray(), $A∪Ø->asArray());
    }

    /**
     * @test Axiom: |A ∪ B| = |A| + |B| - |A ∩ B|
     * The cardinality (count) of unsion of A and B is equal to the cardinality of A + B minus the cardinality of A intersection B
     *
     * @dataProvider dataProviderForTwoSets
     * @param        Set $A
     * @param        Set $B
     */
    public function testCardinalityOfUnion(Set $A, Set $B)
    {
        // Given
        $A∪B = $A->union($B);
        $A∩B = $A->intersect($B);

        // Then
        $this->assertEquals(count($A) + count($B) - count($A∩B), count($A∪B));
        $this->assertEquals(count($A->asArray()) + count($B->asArray()) - count($A∩B->asArray()), count($A∪B->asArray()));
    }

    public function dataProviderForThreeSets(): array
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
     * @test Axiom: A ∩ B = B ∩ A
     * Intersection is commutative
     *
     * @dataProvider dataProviderForTwoSets
     * @param        Set $A
     * @param        Set $B
     */
    public function testIntersectionCommutative(Set $A, Set $B)
    {
        // Given
        $A∩B = $A->intersect($B);
        $B∩A = $B->intersect($A);

        // Then
        $this->assertEquals($A∩B, $B∩A);
        $this->assertEquals($A∩B->asArray(), $B∩A->asArray());
    }

    /**
     * @test Axiom: A ∩ (B ∩ C) = (A ∩ B) ∩ C
     * Intersection is associative
     *
     * @dataProvider dataProviderForThreeSets
     * @param        Set $A
     * @param        Set $B
     * @param        Set $C
     */
    public function testIntersectionAssociative(Set $A, Set $B, Set $C)
    {
        // Given
        $A∩⟮B∩C⟯ = $A->intersect($B->intersect($C));
        $⟮A∩B⟯∩C = $A->intersect($B)->intersect($C);

        // Then
        $this->assertEquals($A∩⟮B∩C⟯, $⟮A∩B⟯∩C);
        $this->assertEquals($A∩⟮B∩C⟯->asArray(), $⟮A∩B⟯∩C->asArray());
    }

    /**
     * @test Axiom: A ∩ (B ∪ C) = (A ∩ B) ∪ (A ∩ C)
     * Intersection is distributive
     *
     * @dataProvider dataProviderForThreeSets
     * @param        Set $A
     * @param        Set $B
     * @param        Set $C
     */
    public function testIntersectionDistributive(Set $A, Set $B, Set $C)
    {
        // Given
        $A∩⟮B∪C⟯    = $A->intersect($B->union($C));
        $⟮A∩B⟯∪⟮A∩C⟯ = $A->intersect($B)->union($A->intersect($C));

        // Then
        $this->assertEquals($A∩⟮B∪C⟯, $⟮A∩B⟯∪⟮A∩C⟯);
        $this->assertEquals($A∩⟮B∪C⟯->asArray(), $⟮A∩B⟯∪⟮A∩C⟯->asArray());
    }

    /**
     * @test Axiom: A ∩ (A ∪ B) = A
     * Intersection absorbtion law
     *
     * @dataProvider dataProviderForTwoSets
     * @param        Set $A
     * @param        Set $B
     */
    public function testIntersectionAbsorbtion(Set $A, Set $B)
    {
        // Given
        $A∩⟮B∪C⟯ = $A->intersect($A->union($B));

        // Then
        $this->assertEquals($A, $A∩⟮B∪C⟯);
        $this->assertEquals($A->asArray(), $A∩⟮B∪C⟯->asArray());
    }

    /**
     * @test Axiom: (A ∩ B) ⊆ A
     * A intersect B is a subset of A
     *
     * @dataProvider dataProviderForTwoSets
     * @param        Set $A
     * @param        Set $B
     */
    public function testAIntersectionBIsSubsetOfA(Set $A, Set $B)
    {
        // Given
        $A∩B = $A->intersect($B);

        // Then
        $this->assertTrue($A∩B->isSubset($A));
        $this->assertTrue($A∩B->isSubset($B));
    }

    /**
     * @test Axiom: A ∩ A = A
     * A intersection A equals A
     *
     * @dataProvider dataProviderForSingleSet
     * @param        Set $A
     */
    public function testAIntersectionAEqualsA(Set $A)
    {
        // Given
        $A∩A = $A->intersect($A);

        // Then
        $this->assertEquals($A, $A∩A);
        $this->assertEquals($A->asArray(), $A∩A->asArray());
    }

    /**
     * @test Axiom: A ∩ Ø = Ø
     * A union empty set is A
     *
     * @dataProvider dataProviderForSingleSet
     * @param        Set $A
     */
    public function testAIntersectionEmptySetIsEmptySet(Set $A)
    {
        // Given
        $Ø   = new Set();
        $A∩Ø = $A->intersect($Ø);

        // Then
        $this->assertEquals($Ø, $A∩Ø);
        $this->assertEquals($Ø->asArray(), $A∩Ø->asArray());
    }

    /**
     * @test Axiom: A ∖ B ≠ B ∖ A for A ≠ B
     * A diff B does not equal B diff A if A and B are different sets
     *
     * @dataProvider dataProviderForTwoSetsDifferent
     * @param        Set $A
     * @param        Set $B
     */
    public function testADiffBDifferentFromBDiffAWhenNotEqual(Set $A, Set $B)
    {
        // Given
        $A∖B = $A->difference($B);
        $B∖A = $B->difference($A);

        // Then
        $this->assertNotEquals($A∖B, $B∖A);
        $this->assertNotEquals($A∖B->asArray(), $B∖A->asArray());
    }

    public function dataProviderForTwoSetsDifferent(): array
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
     * @test Axiom: A ∖ A = Ø
     * A diff itself is the empty set
     *
     * @dataProvider dataProviderForSingleSet
     * @param        Set $A
     */
    public function testADiffItselfIsEmptySet(Set $A)
    {
        // Given
        $Ø   = new Set();
        $A∖A = $A->difference($A);

        // Then
        $this->assertEquals($Ø, $A∖A);
        $this->assertEquals($Ø->asArray(), $A∖A->asArray());
    }

    /**
     * @test Axiom: A Δ B = (A ∖ B) ∪ (B ∖ A)
     * A symmetric different B equals union of A diff B and B diff A
     *
     * @dataProvider dataProviderForTwoSets
     * @param        Set $A
     * @param        Set $B
     */
    public function testASymmetricDifferentBEqualsUnionADiffBAndBDiffA(Set $A, Set $B)
    {
        // Given
        $AΔB       = $A->symmetricDifference($B);
        $A∖B       = $A->difference($B);
        $B∖A       = $B->difference($A);
        $⟮A∖B⟯∪⟮B∖A⟯ = $A∖B->union($B∖A);

        // Then
        $this->assertEquals($AΔB, $⟮A∖B⟯∪⟮B∖A⟯);
        $this->assertEquals($AΔB->asArray(), $⟮A∖B⟯∪⟮B∖A⟯->asArray());
    }

    /**
     * @test Axiom: A × Ø = Ø
     * A cartesian product with empty set is the empty set
     *
     * @dataProvider dataProviderForSingleSet
     * @param        Set $A
     */
    public function testACartesianProductWithEmptySetIsEmptySet(Set $A)
    {
        // Given
        $Ø   = new Set();
        $A×Ø = $A->cartesianProduct($Ø);

        // Then
        $this->assertEquals($Ø, $A×Ø);
    }

    /**
     * @test Axiom: A × (B ∪ C) = (A × B) ∪ (A × C)
     * A cross union of B and C is the union of A cross B and A cross C
     *
     * @dataProvider dataProviderForThreeSets
     * @param        Set $A
     * @param        Set $B
     * @param        Set $C
     */
    public function testACrossUnionBCEqualsACrossBUnionACrossC(Set $A, Set $B, Set $C)
    {
        // Given
        $A×⟮B∪C⟯ = $A->cartesianProduct($B->union($C));
        $⟮A×B⟯∪⟮A×C⟯ = $A->cartesianProduct($B)->union($A->cartesianProduct($C));

        // Then
        $this->assertEquals($A×⟮B∪C⟯, $⟮A×B⟯∪⟮A×C⟯);
        $this->assertEquals($A×⟮B∪C⟯->asArray(), $⟮A×B⟯∪⟮A×C⟯->asArray());
    }

    /**
     * @test Axiom: (A ∪ B) × C = (A × C) ∪ (B × C)
     * A union B cross C is the union of A cross C and B cross C
     *
     * @dataProvider dataProviderForThreeSets
     * @param        Set $A
     * @param        Set $B
     * @param        Set $C
     */
    public function testAUnionBCrossCEqualsUnsionOfACRossCAndBCrossC(Set $A, Set $B, Set $C)
    {
        // Given
        $⟮A∪B⟯×C = $A->union($B)->cartesianProduct($C);
        $⟮A×C⟯∪⟮B×C⟯ = $A->cartesianProduct($C)->union($B->cartesianProduct($C));

        // Then
        $this->assertEquals($⟮A∪B⟯×C, $⟮A×C⟯∪⟮B×C⟯);
        $this->assertEquals($⟮A∪B⟯×C->asArray(), $⟮A×C⟯∪⟮B×C⟯->asArray());
    }

    /**
     * @test Axiom: |A × B| - |A| * |B|
     * The cardinality (count) of the cartesian product is the product of the cardinality of A and B
     *
     * @dataProvider dataProviderForTwoSets
     * @param        Set $A
     * @param        Set $B
     */
    public function testCardinalityOfCartesianProduct(Set $A, Set $B)
    {
        // Given
        $A×B = $A->cartesianProduct($B);

        // Then
        $this->assertEquals(count($A) * count($B), count($A×B));
        $this->assertEquals(count($A->asArray()) * count($B->asArray()), count($A×B->asArray()));
    }

    /**
     * @test Axiom: |S| = n, then |P(S)| = 2ⁿ
     * The cardinality (count) of a power set of S is 2ⁿ if the cardinality of S is n.
     *
     * @dataProvider dataProviderForSingleSet
     * @param        Set $A
     */
    public function testCardinalityOfPowerSet(Set $A)
    {
        // Given
        $P⟮S⟯ = $A->powerSet();
        $n   = count($A);

        // Then
        $this->assertEquals(\pow(2, $n), count($P⟮S⟯));
        $this->assertEquals(\pow(2, $n), count($P⟮S⟯->asArray()));
    }

    /**
     * An M-partial intersection (for M > 0) of N sets is a set elements in which
     * are contained in at least M initial sets.
     *
     * @test
     * @dataProvider dataProviderForPartialIntersectionDefinition
     * @param int $m
     * @param Set ...$sets
     * @return void
     */
    public function testPartialIntersectionDefinition(int $m, Set ...$sets)
    {
        // Given
        $allSets = $sets;
        $s = array_shift($sets);
        $totalElementsCount = array_reduce($allSets, static function (int $carry, Set $set) {
            return $carry + count($set);
        }, 0);
        $partialIntersection = $s->intersectPartial($m, ...$sets);

        // When
        if ($totalElementsCount === 0) {
            // Then
            // Assert than for any M and N M-partial intersection of N empty sets is an empty set.
            $this->assertCount(0, $partialIntersection);
        }

        // Then
        foreach ($partialIntersection as $value) {
            $usageCount = array_reduce($allSets, static function (int $carry, Set $currentSet) use ($value) {
                return $carry + intval($currentSet->isMember($value));
            }, 0);

            // Assert that every element in M-partial intersection occurs in at least M sets.
            $this->assertTrue($usageCount >= $m);
        }

        // Then
        foreach ($allSets as $set) {
            $notInPartialIntersection = $set->difference($partialIntersection);
            foreach ($notInPartialIntersection as $value) {
                $usageCount = array_reduce($allSets, static function (int $carry, Set $currentSet) use ($value) {
                    return $carry + intval($currentSet->isMember($value));
                }, 0);

                // Assert that elements from sets and not in M-partial intersection occurs in less than M sets.
                $this->assertTrue($usageCount < $m);
            }
        }
    }

    public function dataProviderForPartialIntersectionDefinition(): array
    {
        return [
            [
                1,
                new Set(),
            ],
            [
                2,
                new Set(),
            ],
            [
                1,
                new Set([1]),
            ],
            [
                2,
                new Set([1]),
            ],
            [
                1,
                new Set(),
                new Set(),
            ],
            [
                2,
                new Set(),
                new Set(),
            ],
            [
                3,
                new Set(),
                new Set(),
            ],
            [
                1,
                new Set([1]),
                new Set(),
            ],
            [
                2,
                new Set([1]),
                new Set(),
            ],
            [
                1,
                new Set(),
                new Set([1]),
            ],
            [
                2,
                new Set(),
                new Set([1]),
            ],
            [
                1,
                new Set([1]),
                new Set([1]),
            ],
            [
                2,
                new Set([1]),
                new Set([1]),
            ],
            [
                3,
                new Set([1]),
                new Set([1]),
            ],
            [
                1,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
            ],
            [
                2,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
            ],
            [
                3,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
            ],
            [
                1,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
            ],
            [
                2,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
            ],
            [
                3,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
            ],
            [
                1,
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                2,
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                3,
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                4,
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                1,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                2,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                3,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                4,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                1,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
                new Set([11, 12, 13, 14, 15]),
            ],
            [
                2,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
                new Set([11, 12, 13, 14, 15]),
            ],
            [
                3,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
                new Set([11, 12, 13, 14, 15]),
            ],
            [
                4,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
                new Set([11, 12, 13, 14, 15]),
            ],
            [
                1,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                2,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                3,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                4,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                1,
                new Set(),
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                2,
                new Set(),
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                3,
                new Set(),
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                4,
                new Set(),
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                5,
                new Set(),
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                1,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set(),
            ],
            [
                2,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set(),
            ],
            [
                3,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set(),
            ],
            [
                4,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set(),
            ],
            [
                5,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set(),
            ],
            [
                1,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set([3, 4, 5, 6, 7, 8]),
            ],
            [
                2,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set([3, 4, 5, 6, 7, 8]),
            ],
            [
                3,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set([3, 4, 5, 6, 7, 8]),
            ],
            [
                4,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set([3, 4, 5, 6, 7, 8]),
            ],
            [
                5,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set([3, 4, 5, 6, 7, 8]),
            ],
        ];
    }

    /**
     * 1-partial intersection is equivalent to the union of these sets.
     *
     * @test
     * @dataProvider dataProviderForOnePartialIntersectionIsUnion
     * @param Set $s
     * @param Set ...$others
     * @return void
     */
    public function testOnePartialIntersectionIsUnion(Set $s, Set ...$others)
    {
        // Given
        $onePartialIntersection = $s->intersectPartial(1, ...$others);

        // When
        $union = $s->union(...$others);

        // Then
        $this->assertEquals($union, $onePartialIntersection);
    }

    public function dataProviderForOnePartialIntersectionIsUnion(): array
    {
        return [
            [
                new Set(),
                new Set(),
            ],
            [
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                new Set([1]),
                new Set(),
            ],
            [
                new Set(),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([1]),
            ],
            [
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
            ],
            [
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
            ],
            [
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
                new Set([11, 12, 13, 14, 15]),
            ],
            [
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set(),
            ],
            [
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set([3, 4, 5, 6, 7, 8]),
            ],
        ];
    }

    /**
     * 2-partial intersection is equivalent to the difference of the union and the symmetric difference of these sets.
     *
     * @test
     * @dataProvider dataProviderForTwoPartialIntersectionIsDifferenceOfUnionAndSymmetricDifference
     * @param Set $lhs
     * @param Set $rhs
     */
    public function testTwoPartialIntersectionIsDifferenceOfUnionAndSymmetricDifference(Set $lhs, Set $rhs)
    {
        // Given
        $onePartialIntersection = $lhs->intersectPartial(2, $rhs);

        // When
        $union = $lhs->union($rhs);
        $symmetricDifference = $lhs->symmetricDifference($rhs);
        $diffOfUnionAndSymmetricDifference = $union->difference($symmetricDifference);

        // Then
        $this->assertEquals($diffOfUnionAndSymmetricDifference, $onePartialIntersection);
    }

    public function dataProviderForTwoPartialIntersectionIsDifferenceOfUnionAndSymmetricDifference(): array
    {
        return [
            [
                new Set(),
                new Set(),
            ],
            [
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                new Set([1]),
                new Set(),
            ],
            [
                new Set(),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([1]),
            ],
            [
                new Set(),
                new Set([1, 2, 3]),
            ],
            [
                new Set([1, 2, 3]),
                new Set(),
            ],
            [
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
            ],
            [
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
            ],
            [
                new Set([2, 3, 4, 5, 6]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
            ],
        ];
    }

    /**
     * N-partial intersection is equivalent to the common (complete) intersection of these sets.
     *
     * @test
     * @dataProvider dataProviderForNPartialIntersectionIsCompleteIntersection
     * @param Set $s
     * @param Set ...$others
     * @return void
     */
    public function testNPartialIntersectionIsCompleteIntersection(Set $s, Set ...$others)
    {
        // Given
        $n = count($others) + 1;
        $onePartialIntersection = $s->intersectPartial($n, ...$others);

        // When
        $union = $s->intersect(...$others);

        // Then
        $this->assertEquals($union, $onePartialIntersection);
    }

    public function dataProviderForNPartialIntersectionIsCompleteIntersection(): array
    {
        return [
            [
                new Set(),
                new Set(),
            ],
            [
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                new Set([1]),
                new Set(),
            ],
            [
                new Set(),
                new Set([1]),
            ],
            [
                new Set([1]),
                new Set([1]),
            ],
            [
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
            ],
            [
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
            ],
            [
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
                new Set([11, 12, 13, 14, 15]),
            ],
            [
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set(),
            ],
            [
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set([3, 4, 5, 6, 7, 8]),
            ],
        ];
    }

    /**
     * For any M > N M-partial intersection always equals to the empty set.
     *
     * @test
     * @dataProvider dataProviderForMPartialIntersectionIsEmptySetWhenMMoreThanN
     * @param int $m
     * @param Set $s
     * @param Set ...$others
     * @return void
     */
    public function testMPartialIntersectionIsEmptySetWhenMMoreThanN(int $m, Set $s, Set ...$others)
    {
        // Given
        $emptySet = new Set();
        $n = count($others) + 1;

        // When
        $this->assertTrue($m > $n);
        $partialIntersection = $s->intersectPartial($m, ...$others);

        // Then
        $this->assertEquals($emptySet, $partialIntersection);
    }

    public function dataProviderForMPartialIntersectionIsEmptySetWhenMMoreThanN(): array
    {
        return [
            [
                3,
                new Set(),
                new Set(),
            ],
            [
                4,
                new Set(),
                new Set(),
            ],
            [
                4,
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                5,
                new Set(),
                new Set(),
                new Set(),
            ],
            [
                3,
                new Set([1]),
                new Set(),
            ],
            [
                4,
                new Set([1]),
                new Set(),
            ],
            [
                3,
                new Set(),
                new Set([1]),
            ],
            [
                4,
                new Set(),
                new Set([1]),
            ],
            [
                3,
                new Set([1]),
                new Set([1]),
            ],
            [
                4,
                new Set([1]),
                new Set([1]),
            ],
            [
                3,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
            ],
            [
                4,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
            ],
            [
                3,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
            ],
            [
                4,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
            ],
            [
                4,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                5,
                new Set([1, 2, 3, 4, 5]),
                new Set([2, 3, 4, 5, 6]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                4,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
                new Set([11, 12, 13, 14, 15]),
            ],
            [
                5,
                new Set([1, 2, 3, 4, 5]),
                new Set([6, 7, 8, 9, 10]),
                new Set([11, 12, 13, 14, 15]),
            ],
            [
                4,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                5,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
            ],
            [
                5,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set(),
            ],
            [
                6,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set(),
            ],
            [
                5,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set([3, 4, 5, 6, 7, 8]),
            ],
            [
                6,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set([3, 4, 5, 6, 7, 8]),
            ],
            [
                100,
                new Set([1, 2, 3]),
                new Set([2, 3, 4, 5]),
                new Set([3, 4, 5, 6, 7]),
                new Set([3, 4, 5, 6, 7, 8]),
            ],
        ];
    }
}
