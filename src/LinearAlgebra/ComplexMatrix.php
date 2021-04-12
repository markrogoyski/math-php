<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;
use MathPHP\Number\Complex;

class ComplexMatrix extends ObjectMatrix
{
    /** @var Complex[][] Matrix array of arrays */
    protected $A;

    public function __construct(array $A)
    {
        parent::__construct($A);

        $this->validateComplexData();
    }

    /**
     * Validate the matrix is entirely complex
     *
     * @throws Exception\IncorrectTypeException if all elements are not complex
     */
    protected function validateComplexData()
    {
        foreach ($this->A as $i => $row) {
            foreach ($row as $object) {
                if (!$object instanceof Complex) {
                    throw new Exception\IncorrectTypeException("All elements in the complex matrix must be complex. Got " . \get_class($object));
                }
            }
        }
    }

    /**
     * Conjugate Transpose - Aᴴ, also denoted as A*
     *
     * Take the transpose and then take the complex conjugate of each complex-number entry.
     *
     * https://en.wikipedia.org/wiki/Conjugate_transpose
     *
     * @return ComplexMatrix
     */
    public function conjugateTranspose(): Matrix
    {
        return $this->transpose()->map(
            function (Complex $c) {
                return $c->complexConjugate();
            }
        );
    }
}
