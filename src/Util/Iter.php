<?php

namespace MathPHP\Util;

use MathPHP\Exception;

/**
 * @internal
 */
class Iter
{
    /**
     * Zip - Make an iterator that aggregates items from multiple iterators
     * Similar to Python's zip function
     * @internal
     *
     * @param iterable ...$iterables
     *
     * @return \MultipleIterator
     *
     * @throws Exception\BadDataException if one of the parametesr is nto iterable
     */
    public static function zip(...$iterables): \MultipleIterator
    {
        $zippedIterator = new \MultipleIterator();
        foreach ($iterables as $iterable) {
            $zippedIterator->attachIterator(self::makeIterator($iterable));
        }

        return $zippedIterator;
    }

    /**
     * @param iterable $iterable
     *
     * @return \Iterator|\IteratorIterator|\ArrayIterator
     *
     * @throws Exception\BadDataException if the parameter is not iterable
     */
    private static function makeIterator($iterable): \Iterator
    {
        switch (true) {
            case $iterable instanceof \Iterator:
                return $iterable;

            case $iterable instanceof \Traversable:
                return new \IteratorIterator($iterable);

            case \is_array($iterable):
                return new \ArrayIterator($iterable);

            default:
                $type = \gettype($iterable);
                throw new Exception\BadDataException("'{$type}' type is not iterable");
        }
    }
}
