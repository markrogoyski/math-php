<?php
namespace Math\Functions;

class Support
{
    /**
     * Checks that the values of the parameters passed
     * to a function fall within the defined bounds.
     * The parameter limits are defined using ISO 31-11 notation.
     * https://en.wikipedia.org/wiki/ISO_31-11
     *
     *  (a,b) = a <  x <  b
     *  [a,b) = a <= x <  b
     *  (a,b] = a <  x <= b
     *  [a,b] = a <= x <= b
     *
     * @param array $limits Boundary limit definitions for each parameter
     *                      ['var1' => limit, 'var2' => limit, ...]
     * @param array $params Parameters and their value to check against the defined limits
     *                      ['var1' => value, 'var2' => value, ...]
     *
     * @return bool True if all parameters are within defined limits
     *
     * @throws \Exception if any parameter is outside the defined limits
     */
    public static function checkLimits(array $limits, array $params)
    {
        // All parameters should have limit bounds defined
        $undefined_limits = array_diff_key($params, $limits);
        if (!empty($undefined_limits)) {
            throw new \Exception('Parameter without bounds limit defined: ' . print_r($undefined_limits, true));
        }

        foreach ($params as $variable => $value) {
            // Remove the first character: ( or [
            $lower_endpoint = substr($limits[$variable], 0, 1);
            
            // Remove the last character: ) or ]
            $upper_endpoint = substr($limits[$variable], -1, 1);
            
            // Set the lower and upper limits: #,#
            list($lower_limit, $upper_limit) = explode(',', substr($limits[$variable], 1, -1));
            
            // If the lower limit is -∞, we are always in bounds.
            if ($lower_limit != "-∞") {
                switch ($lower_endpoint) {
                    case '(':
                        if ($value <= $lower_limit) {
                            throw new \Exception("{$variable} must be > {$lower_limit}");
                        }
                        break;
                    case '[':
                        if ($value < $lower_limit) {
                            throw new \Exception("{$variable} must be >= {$lower_limit}");
                        }
                        break;
                    default:
                        throw new \Exception("Unknown lower endpoint character: {$lower_limit}");
                }
            }
            
            // If the upper limit is ∞, we are always in bounds.
            if ($upper_limit != "∞") {
                switch ($upper_endpoint) {
                    case ')':
                        if ($value >= $upper_limit) {
                            throw new \Exception("{$variable} must be < {$upper_limit}");
                        }
                        break;
                    case ']':
                        if ($value > $upper_limit) {
                            throw new \Exception("{$variable} must be <= {$upper_limit}");
                        }
                        break;
                    default:
                        throw new \Exception("Unknown upper endpoint character: {$upper_endpoint}");
                }
            }
        }

        return true;
    }
}
