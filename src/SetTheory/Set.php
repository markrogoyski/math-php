<?php
namespace Math\SetTheory;

/**
 * Set (Set Theory)
 * A set is a collection of distinct objects, considered as an object in
 * its own right.
 * https://en.wikipedia.org/wiki/Set_(mathematics)
 *
 * Sets can contain numbers, strings, and other sets.
 *
 * Implementation:
 * For performance reasons, PHP arrays are used as a hash for quick access
 * via hash keys. However, when storing a Set object as a set member, in
 * order to get the original Set object back, such as when iterating through
 * a set or getting the set as an Array, the values of the array are used to
 * store the original object.
 *
 * When storing a Set object as a member of a set, its key will be a string
 * that uses mathematical set notation, for example: '{1, 2, 3}'.
 * The one edge case of this, is that the Set object {1, 2, 3} and the string
 * '{1, 2, 3}' would appear identical in the case of adding one when the other
 * already is a member of the set. When accessing the actual set member, you
 * will always get back the original one added, whether it was a Set object or
 * a string.
 */
class Set implements \Countable, \Iterator
{
    /**
     * Set as a hash. Keys are the members of the set.
     * Values are just set to true as they are not used.
     * @var array
     */
    protected $A = [];

    /**
     * Constructor - Initialize set members
     *
     * @param array $members
     */
    public function __construct(array $members = [])
    {
        foreach ($members as $member) {
            $this->A["$member"] = $member;
        }
    }

    /**************************************************************************
     * GET SET CONTENTS
     *  - Array
     *  - Hash
     *  - Length
     **************************************************************************/

    /**
     * Get the set as an array
     *
     * @return array (values are the set members)
     */
    public function asArray(): array
    {
        return $this->A;
    }

    /**
     * Get length of set (number of members in set)
     *
     * @return int
     */
    public function length(): int
    {
        return count($this->A);
    }

    /**************************************************************************
     * SET PROPERTIES
     *  - Empty set
     **************************************************************************/

    public function isEmpty(): bool
    {
        return empty($this->A);
    }

    /**************************************************************************
     * SINGLE MEMBER PROPERTIES
     *  - Is member
     *  - Is not member
     **************************************************************************/

    /**
     * Set membership (x ∈ A)
     * Is x a member of the set?
     *
     * @param  mixed $x
     *
     * @return boolean
     */
    public function isMember($x): bool
    {
        return array_key_exists("$x", $this->A);
    }

    /**
     * Set non-membership (x ∉ A)
     * Is x not a member of the set?
     *
     * @param  mixed $x
     *
     * @return boolean
     */
    public function isNotMember($x): bool
    {
        return !array_key_exists("$x", $this->A);
    }

    /**************************************************************************
     * SINGLE MEMBER OPERATIONS
     *  - Add
     *  - Remove
     **************************************************************************/

    /**
     * Add an element to the set
     * Does nothing if element already exists in the set.
     *
     * @param mixed $x
     *
     * @return Set (this set)
     */
    public function add($x): Set
    {
        if (is_int($x) || is_float($x) || is_string($x) || $x instanceof Set) {
            $this->A["$x"] = $x;
            return $this;
        }

        if (is_array($x)) {
            $members = [];
            foreach ($x as $member) {
                $members["$member"] = $member;
            }
            $new_members = array_diff_key($members, $this->A);
            foreach ($new_members as $member => $_) {
                if (is_int($member) || is_float($member) || is_string($member) || $member instanceof Set) {
                    $this->A["$member"] = $member;
                }
            }
            return $this;
        }

        return $this;
    }

    /**
     * Remove an elment from the set
     * Does nothing if the element does not exist in the set.
     *
     * @param  mixed $x
     *
     * @return Set (this set)
     */
    public function remove($x): Set
    {
        if (is_int($x) || is_float($x) || is_string($x) || $x instanceof Set) {
            unset($this->A["$x"]);
            return $this;
        }

        if (is_array($x)) {
            $members = [];
            foreach ($x as $member) {
                $members["$member"] = true;
            }
            $actual_members = array_intersect_key($members, $this->A);
            foreach ($actual_members as $member => $_) {
                unset($this->A["$member"]);
            }
        }

        return $this;
    }

    /**************************************************************************
     * SET PROPERTIES AGAINST OTHER SETS
     *  - Disjoint
     *  - Subset
     *  - Proper subset
     *  - Super set
     *  - Proper superset
     **************************************************************************/

    /**
     * Disjoint
     * Does the set have no elements in common with the other set?
     *
     * Example of disjoint sets:
     *  A = {1, 2, 3}
     *  B = {4, 5, 6}
     *
     * @param  Set $other
     *
     * @return boolean
     */
    public function isDisjoint(Set $other): bool
    {
        return empty(array_intersect_key($this->A, $other->asArray()));
    }

    /**
     * Subset (A ⊆ B)
     * Is the set a subset of the other set?
     * In other words, does the other set contain all the elements of the set?
     *
     * @param  Set $B
     *
     * @return boolean
     */
    public function isSubset(Set $B): bool
    {
        $B_array  = $B->asArray();

        $intersection = array_intersect_key($this->A, $B_array);
        $difference   = array_diff_key($this->A, $B_array);

        return (count($intersection) === count($this->A)) && (empty($difference));
    }

    /**
     * Proper subset (A ⊆ B & A ≠ B)
     * Is the set a proper subset of the other set?
     * In other words, does the other set contain all the elements of the set,
     * and the set is not the same set as the other set?
     *
     * @param  Set $B
     *
     * @return boolean
     */
    public function isProperSubset(Set $B): bool
    {
        $B_array  = $B->asArray();

        $intersection = array_intersect_key($this->A, $B_array);
        $difference   = array_diff_key($this->A, $B_array);

        return (count($intersection) === count($this->A)) && (empty($difference)) && (count($this->A) === count($B));
    }

    /**
     * Superset (A ⊇ B)
     * Is the set a superset of the other set?
     * In other words, does the the set contain all the elements of the other set?
     *
     * @param  Set $B
     *
     * @return boolean
     */
    public function isSuperset(Set $B): bool
    {
        $B_array  = $B->asArray();

        $intersection = array_intersect_key($this->A, $B_array);
        $difference   = array_diff_key($B_array, $this->A);

        return (count($intersection) === $B->length()) && (empty($difference));
    }

    /**
     * Superset (A ⊇ B & A ≠ B)
     * Is the set a superset of the other set?
     * In other words, does the the set contain all the elements of the other set,
     * and the set is not the same set as the other set?
     *
     * @param  Set $B
     *
     * @return boolean
     */
    public function isProperSuperset(Set $B): bool
    {
        $B_array  = $B->asArray();

        $intersection = array_intersect_key($this->A, $B_array);
        $difference   = array_diff_key($B_array, $this->A);

        return (count($intersection) === $B->length()) && (empty($difference)) && ($this != $B);
    }

    /**************************************************************************
     * SET OPERATIONS ON OTHER SETS
     *  - Union
     *  - Intersection
     *  - Difference
     *  - Symmetric difference
     **************************************************************************/

    /**
     * Union (A ∪ B)
     * Produces a new set with all elements from all sets.
     *
     * Example:
     *  {1, 2} ∪ {2, 3} = {1, 2, 3}
     *
     * @param  Set ...$Bs One or more sets
     *
     * @return Set
     */
    public function union(Set ...$Bs): Set
    {
        $union       = $this->A;
        $new_members = [];

        foreach ($Bs as $B) {
            $new_members += array_diff_key($B->asArray(), $union);
        }

        foreach ($new_members as $member => $value) {
            $union[$member] = $value;
        }

        return new Set($union);
    }

    /**
     * Intersect (A ∩ B)
     * Produces a new set with all the elements common to all sets.
     *
     * Example:
     *  {1, 2} ∩ {2, 3} = {2}
     *
     * @param  Set ...$Bs One or more sets
     *
     * @return Set
     */
    public function intersect(Set ...$Bs): Set
    {
        $other_members = [];
        foreach ($Bs as $B) {
            $B_members[] = $B->asArray();
        }

        $intersection = array_intersect_key($this->A, ...$B_members);

        return new Set($intersection);
    }

    /**
     * Difference (relative complement) (A ∖ B) or (A - B)
     * Produces a new set with elements that are not in the other sets.
     *
     * @param  Set ...$Bs One or more sets
     *
     * @return Set
     */
    public function difference(Set ...$Bs): Set
    {
        $B_members = [];
        foreach ($Bs as $B) {
            $B_members += $B->asArray();
        }

        $difference = array_diff_key($this->A, $B_members);

        return new Set($difference);
    }

    /**
     * Symmetric Difference (A Δ B) = (A ∖ B) ∪ (B ∖ A)
     * Produces a new set with elements that are in the set or the other,
     * but not both.
     *
     * Example:
     *  {7, 8, 9, 10} Δ {9, 10, 11, 12} = {7, 8, 11, 12}
     *
     * @param  Set $B
     *
     * @return Set
     */
    public function symmetricDifference(Set $B)
    {
        $B_array = $B->asArray();

        $intersection = array_intersect_key($this->A, $B_array);

        $difference1 = array_diff_key($this->A, $intersection);
        $difference2 = array_diff_key($B_array, $intersection);

        return new Set($difference1 + $difference2);
    }

    /**
     * Cartesian product (A×B)
     * Produces a new set by associating every element of the set with every
     * element of the other set.
     *
     * Example:
     *  A   = (1, 2)
     *  B   = (a, b)
     *  A×B = ((1, a), (1, b), (2, 1), (2, b))
     *
     * @param  Set $B
     *
     * @return Set
     */
    public function cartesianProduct(Set $B)
    {
        $product = [];

        foreach ($this->A as $memberA) {
            foreach ($B->asArray() as $memberB) {
                $product[] = new Set([$memberA, $memberB]);
            }
        }

        return new Set($product);
    }

    /**************************************************************************
     * OTHER SET OPERATIONS
     *  - Copy
     *  - Clear
     *  - To string
     **************************************************************************/

    /**
     * Copy
     * Produces a new set with the same elements as the set.
     *
     * @return Set
     */
    public function copy(): Set
    {
        // ImmutableSet extends Set, so return the calling class' type.
        return new static($this->A);
    }

    /**
     * Clear the set. Removes all members.
     * Results in an empty set.
     *
     * @return Set (this set)
     */
    public function clear(): Set
    {
        $this->A = [];

        return $this;
    }

    /**
     * Return the set as a string
     * (a, b, c, ...)
     *
     * @return string
     */
    public function __toString(): string
    {
        if ($this->isEmpty()) {
            return 'Ø';
        }
        return '{' . implode(', ', array_keys($this->A)) . '}';
    }

    /**************************************************************************
     * PHP INTERFACES
     *  - Countable
     *  - Iterator (Traversable)
     **************************************************************************/

    /**
     * Countable interface
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->A);
    }

    /**
     * Iterator interface array to iterate over
     * @var array
     */
    protected $iterator_keys;

    /**
     * Iterator interface position of iterator keys we are at (the key)
     * @var mixed
     */
    protected $iterator_position;

    /**
     * Rewind (Iterator interface)
     */
    public function rewind()
    {
        $this->iterator_keys     = array_keys($this->A);
        $this->iterator_position = array_shift($this->iterator_keys);
    }

    /**
     * Valid (Iterator interface)
     *
     * @return boolean
     */
    public function valid(): bool
    {
        return isset($this->A[$this->iterator_position]);
    }

    /**
     * Current (Iterator interface)
     */
    public function current()
    {
        return $this->A[$this->iterator_position];
    }

    /**
     * Key (Iterator interface)
     */
    public function key()
    {
        return $this->iterator_position;
    }

    /**
     * Next (Iterator interface)
     */
    public function next()
    {
        $this->iterator_position = array_shift($this->iterator_keys);
    }
}
