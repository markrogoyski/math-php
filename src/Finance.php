<?php
namespace Math;

class Finance
{
    /**
     * Financial payment for a loan with compound interest.
     * Determines the periodic payment amount for a given interest rate,
     * load principal, targeted payment goal, life of the loan as number
     * of payments, and whether the payments are made at the start or end
     * of each payment period.
     *
     * Same as the =PMT() function in most spreadsheet software.
     *
     * @param  float $rate
     * @param  int $periods
     * @param  float $present_value
     * @param  float $future_value
     * @param  bool beginning
     *
     * @return float
     */
    public static function pmt(float $rate, int $periods, float $present_value, float $future_value = 0, bool $beginning = false): float
    {
        $when = 0;
        if ($beginning) {
          $when = 1;
        }
        if ($rate == 0) {
          return - ($future_value + $present_value) / $periods;
        }
        return - ($future_value + ($present_value * pow(1 + $rate, $periods)))
            /
            ((1 + $rate*$when) / $rate * (pow(1 + $rate, $periods) - 1));
    }
}
