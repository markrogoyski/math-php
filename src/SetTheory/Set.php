<?php
namespace Math\SetTheory;

/**
 * Set (Set Theory)
 * A set is a collection of distinct objects, considered as an object in
 * its own right.
 * https://en.wikipedia.org/wiki/Set_(mathematics)
 *
 * Sets can contain numbers, strings, arrays, objects, and other sets.
 *
 * Implementation:
 * For performance reasons, PHP arrays are used as a hash for quick access
 * via hash keys.
 *
 * The hash keys are as follows:
 *  - Numbers and strings: value itself
 *  - Sets: Set as a string.
 *  - Arrays: Array(array_serialization)
 *  - Objects: Object\Name(object_hash)
 *
 * The values of the associative array (hash) are the actual values or
 * objects themselves. If the set is iterated in a foreach loop you will
 * get back the original value, set, array, or object.
 *
 * An object cannot be in the set multiple times. For a regular value, like
 * a number or string, this is straight forward. For arrays and objects, the
 * behavior is based on whether they are the same thing. What that means depends
 * on whether it is an array or object.
 *
 * Example (arrays):
 * $array1 = [1, 2, 3];
 * $array2 = [1, 2, 3];
 * $set = new Set([$array1, $array2]);
 *
 * The set will have only one element, because the arrays are equal.
 * $array2 === $array2 evaluates to true.
 *
 * Example (different objects):
 * $object1 = new \StdClass();
 * $object2 = new \StdClass();
 * $set = new Set([$object1, $object2]);
 *
 * The set will have two elements, because they are different objects.
 * $object1 === $object2 evaluates to false.
 *
 * Example (same objects):
 * $object1 = new \StdClass();
 * $object2 = $object1;
 * $set = new Set([$object1, $object2]);
 *
 * The set will have only one element, because the objects are the same.
 * $object1 === $object2 evaluates to true.
 *
 * Example (Sets, a special case of object)
 * $set1 = new Set([1, 2, 3]);
 * $set2 = new Set([1, 2, 3]);
 * $set3 = new Set([$set1, $set2]);
 *
 * Set3 will have only one element, because sets 1 and 2 are the same. Sets are
 * not based on whether the object is the same, but whether the content of
 * the set are the same. Sets and arrays act similarly.
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
     * Set as a hash.
     * Keys are a representation of the members of the set.
     * Values are the values/objects themselves.
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
            $this->addMember($member);
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
     * Add an element or array of elements to the set
     * Does nothing if element already exists in the set.
     *
     * @param mixed $x Can be scalar or array
     *
     * @return Set (this set)
     */
    public function add($x): Set
    {
        if (!is_array($x)) {
            return $this->addMember($x);
        }

        if (is_array($x)) {
            foreach ($x as $member) {
                $this->addMember($member);
            }
        }

        return $this;
    }

    /**
     * Actually add a new member to the set
     *
     * Based on the type of member to be added, the key differs:
     *  - Number: value as is
     *  - String: value as is
     *  - Set: String representation of set. Example: {1, 2}
     *  - Array: Array(array_serialization)
     *  - Object: Class\Name(object_hash)
     *
     * @param mixed $x
     */
    protected function addMember($x)
    {
        if (is_int($x) || is_float($x) || is_string($x) || $x instanceof Set) {
            $this->A["$x"] = $x;
        } elseif (is_object($x)) {
            $key = get_class($x) . '(' . spl_object_hash($x) . ')';
            $this->A[$key] = $x;
        } elseif (is_array($x)) {
            $key = 'Array(' . serialize($x) . ')';
            $this->A[$key] = $x;
        }

        return $this;
    }

    /**
     * Remove an element or elements from the set
     * Does nothing if the element does not exist in the set.
     *
     * @param  mixed $x
     *
     * @return Set (this set)
     */
    public function remove($x): Set
    {
        if (!is_array($x)) {
            return $this->removeMember($x);
        }

        if (is_array($x)) {
            foreach ($x as $member) {
                $this->removeMember($member);
            }
        }

        return $this;
    }

    /**
     * Actually remove an element from the set
     *
     * @param  mixed $x
     *
     * @return Set
     */
    protected function removeMember($x)
    {
        if (is_int($x) || is_float($x) || is_string($x) || $x instanceof Set) {
            unset($this->A["$x"]);
        } elseif (is_object($x)) {
            $key = get_class($x) . '(' . spl_object_hash($x) . ')';
            unset($this->A[$key]);
        } elseif (is_array($x)) {
            $key = 'Array(' . serialize($x) . ')';
            unset($this->A[$key]);
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
