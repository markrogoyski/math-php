<?php
namespace Math;

class Finance
{
    /**
     * Financial payment for a loan or anuity with compound interest.
     * Determines the periodic payment amount for a given interest rate,
     * principal, targeted payment goal, life of the anuity as number
     * of payments, and whether the payments are made at the start or end
     * of each payment period.
     *
     * Same as the =PMT() function in most spreadsheet software.
     *
     * The basic monthly payment formula derivation:
     * https://en.wikipedia.org/wiki/Mortgage_calculator#Monthly_payment_formula
     *
     *       rP(1+r)ᴺ
     * PMT = --------
     *       (1+r)ᴺ-1
     *
     * The formula is adjusted to allow targeting any future value rather than 0.
     * The 1/(1+r*when) factor adjusts the payment to the beginning or end
     * of the period. In the common case of a payment at the end of a period,
     * the factor is 1 and reduces to the formula above.
     *
     * Examples:
     * The payment on a 30-year fixed mortgage note of $265000 at 3.5% interest
     * paid at the end of every month.
     *   pmt(0.035/12, 30*12, 265000, 0, false)
     *
     * The payment on a 30-year fixed mortgage note of $265000 at 3.5% interest
     * needed to half the principal in half in 5 years:
     *   pmt(0.035/12, 5*12, 265000, 265000/2, false)
     *
     * The weekly payment into a savings account with 1% interest rate and current
     * balance of $1500 needed to reach $10000 after 3 years:
     *   pmt(0.01/52, 3*52, -1500, 10000, false)
     * The present_value is negative indicating money put into the savings account,
     * whereas future_value is positive, indicating money that will be withdrawn from
     * the account. Similarly, the payment value is negative
     *
     * How much money can be withdrawn at the end of every quarter from an account
     * with $1000000 earning 4% so the money lasts 20 years:
     *  pmt(0.04/4, 20*4, 1000000, 0, false)
     *
     * @param  float $rate
     * @param  int   $periods
     * @param  float $present_value
     * @param  float $future_value
     * @param  bool  $beginning adjust the payment to the beginning or end of the period
     *
     * @return float
     */
    public static function pmt(float $rate, int $periods, float $present_value, float $future_value = 0, bool $beginning = false): float
    {
        $when = $beginning ? 1 : 0;

        if ($rate == 0) {
            return - ($future_value + $present_value) / $periods;
        }

        return - ($future_value + ($present_value * pow(1 + $rate, $periods)))
            /
            ((1 + $rate*$when) / $rate * (pow(1 + $rate, $periods) - 1));
    }
}
