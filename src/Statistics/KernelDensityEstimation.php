<?php
namespace MathPHP\Statistics;

use MathPHP\Exception;
use MathPHP\Functions\Map\Single;
use MathPHP\Probability\Distribution\Continuous\StandardNormal;
use MathPHP\Statistics\Descriptive;

/**
 * Kernel Density Estimate
 * https://en.wikipedia.org/wiki/Kernel_density_estimation
 *
 *                       ____
 *            1          \         / (x - xᵢ) \
 * KDE(x) = -----   *     >     K |  -------   |
 *          n * h        /         \    h     /
 *                       ‾‾‾‾
 * The kernel function K, must be a non-negative function with a mean of 0 and integrates to 1
 */
class KernelDensityEstimation
{
    /**
     * number of data points
     * @var number
     */
    protected $n;
    
    /**
     * bandwidth
     * @var number
     */
    protected $h;
    
    /**
     * The kernel function
     * @var callable
     */
    protected $kernel;

    const NORMAL       = 0;
    const UNIFORM      = 1;
    const TRIANGULAR   = 2;
    const EPANECHNIKOV = 3;
    const TRICUBE      = 4;

    /**
     * constructor
     *
     * @param array $data data used for the estimation
     * @param float $h the bandwidth
     * @param callable or int $kernel a function used to generate the KDE
     *
     * @throws OutOfBoundsException if n is < 1 or h <= 0
     */
    public function __construct(array $data, float $h = null, $kernel = null)
    {
        $this->n = count($data);
        if ($this->n === 0) {
            throw new Exception\OutOfBoundsException("Dataset cannot be empty.");
        }
        $this->data = $data;

        if ($h === null) {
            $h = (4 * Descriptive::StandardDeviation($data) ** 5 / 3 / $this->n) ** .2;
        }
        $this->setBandwidth($h);

        if ($kernel === null) {
            $kernel = self::NORMAL;
        }
        $this->setKernelFunction($kernel);
    }
    
    /**************************************************************************
     * SETTERS
     **************************************************************************/

    /**
     * Set Bandwidth
     *
     * @param float $h the bandwidth
     *
     * @throws OutOfBoundsException if h <= 0
     */
    public function setBandwidth(float $h)
    {
        if ($h <= 0) {
            throw new Exception\OutOfBoundsException("Bandwidth must be > 0. h = $h");
        }
        $this->h = $h;
    }

    /**
     * Set The Kernel Function
     *
     * If the parameter is a string, check that there is a function with that name
     * in the "library". If it's a callable, use that function.
     *
     * @throws BadParameterException if $kernel is not an int or callable
     */
    public function setKernelFunction($kernel)
    {
        if (is_int($kernel)) {
            switch ($kernel) {
                case self::UNIFORM:
                    $kernel = function ($x) {
                        if (abs($x) > 1) {
                            return 0;
                        } else {
                            return .5;
                        }
                    };
                    break;
                case self::TRIANGULAR:
                    $kernel = function ($x) {
                        if (abs($x) > 1) {
                            return 0;
                        } else {
                            return 1 - abs($x);
                        }
                    };
                    break;
                case self::EPANECHNIKOV:
                    $kernel = function ($x) {
                        if (abs($x) > 1) {
                            return 0;
                        } else {
                            return .75 * (1 - $x ** 2);
                        }
                    };
                    break;
                case self::TRICUBE:
                    $kernel = function ($x) {
                        if (abs($x) > 1) {
                            return 0;
                        } else {
                            return 70 / 81 * ((1 - abs($x) ** 3) ** 3);
                        }
                    };
                    break;
                default:
                    $kernel = ['MathPHP\Probability\Distribution\Continuous\StandardNormal', 'pdf'];
                    break;
            }
            $this->kernel = $kernel;
        } elseif (is_callable($kernel)) {
            $this->kernel = $kernel;
        } else {
            throw new Exception\BadParameterException('Kernel must be an integer or a callable. Type is: ' . gettype($kernel));
        }
    }

    /**
     * Evaluate
     *
     * Evaluate the kernel density estimation at $x
     *
     * @param float $x the value to evaluate
     *
     * @return float the kernel density estimate at $x
     */
    public function evaluate($x)
    {
        // Scale data to (x - xi) / h and evaluate with the kernel function
        $scale1 = Single::subtract($this->data, $x);
        $scale2 = Single::divide($scale1, -1 * $this->h);
        return 1 / $this->n / $this->h * array_sum(array_map($this->kernel, $scale2));
    }
}
