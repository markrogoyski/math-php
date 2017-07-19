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

    /**
     * constructor
     *
     * @param array $data data used for the estimation
     * @param float $h the bandwidth
     * @param callable $kernel a function used to generate the KDE
     *
     * @throws OutOfBoundsException if n is < 1 or h <= 0
     */
    public function __construct(array $data, float $h = null, callable $kernel = null)
    {
        $this->n = count($data);
        if ($this->n === 0) {
            throw new Exception\OutOfBoundsException("Dataset cannot be empty.");
        }
        $this->data = $data;

        if ($h === null) {
            $this->h = (4 * Descriptive::StandardDeviation($data) ** 5 / 3 / $this->n) ** .2;
        } else {
            $this->h = $h;
        }
        if ($kernel === null) {
            $this->kernel = ['MathPHP\Probability\Distribution\Continuous\StandardNormal', 'pdf'];
        } else {
            $this->kernel = $kernel;
        }
        if ($this->h <= 0) {
            throw new Exception\OutOfBoundsException("h must be > 0. h = $this->h");
        }
    }

    /**
     * KernelDensity
     *
     * @param float $x the value to evaluate
     *
     * @return float the kernel density estimate at $x
     */
    public function KernelDensity($x)
    {
        // Scale data to (x - xi) / h and evaluate with the kernel function
        $scale1 = Single::subtract($this->data, $x);
        $scale2 = Single::divide($scale1, -1 * $this->h);
        return 1 / $this->n / $this->h * array_sum(array_map($this->kernel, $scale2));
    }
}
