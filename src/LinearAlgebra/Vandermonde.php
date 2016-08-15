<?php
namespace Math\LinearAlgebra;

class Vandermonde extends Matrix
{
    /**
     * Create the Vandermonde Matrix from a simple array.
     * 
     * @param $M array (a,b,c,d,e)
     * @param $n int
     *
     */
    public function __construct(array $M, int $n)
    {
        $this->n = $n;
        $this->m = count($M);
        
        $A = [];
        foreach($M as $row=>$value){
            for ($i=0;$i<$n; $i++){
                $A[$row][$i] = $value ** $i;
            }
        }
        $this->A = $A;
    }
}
