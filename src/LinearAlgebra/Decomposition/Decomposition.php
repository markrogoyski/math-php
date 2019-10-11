<?php

namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\Exception;
use MathPHP\LinearAlgebra\Matrix;

abstract class Decomposition implements \ArrayAccess
{
    abstract public static function decompose(Matrix $M);

    /**************************************************************************
     * ArrayAccess INTERFACE
     **************************************************************************/

    /**
     * @param  mixed $i
     * @param mixed $i
     * @return bool
     */
    abstract public function offsetExists($i): bool;

    /**
     * @param mixed $i
     * @return mixed
     */
    public function offsetGet($i)
    {
        return $this->$i;
    }

    /**
     * @param  mixed $i
     * @param  mixed $value
     * @throws Exception\MatrixException
     */
    public function offsetSet($i, $value)
    {
        throw new Exception\MatrixException(get_called_class() . ' class does not allow setting values');
    }

    /**
     * @param  mixed $i
     * @throws Exception\MatrixException
     */
    public function offsetUnset($i)
    {
        throw new Exception\MatrixException(get_called_class() . ' class does not allow unsetting values');
    }
}
