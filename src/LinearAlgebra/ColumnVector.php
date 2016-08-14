<?php
namespace Math\LinearAlgebra;
class ColumnVector extends Matrix
{
    /**
     * Allows the creation of a Matrix from an array
     * instead of an array of arrays.
     */
    public function __construct(array $M)
    {
        $this->n = 1;
        $this->m = count($M);
        $A = [];
        foreach($M as $value){
            $A[] = [$value];
        }
        $this->A = $A;
    }
}
