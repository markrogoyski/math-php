<?php
namespace MathPHP;

class Finance
{
    /**
     * Floating-point range near zero to consider insignificant.
     */
    const EPSILON = 1e-6;

    /**
     * Consider any floating-point value less than epsilon from zero as zero,
     * ie any value in the range [-epsilon < 0 < epsilon] is considered zero.
     * Also used to convert -0.0 to 0.0.
     */
    private static function checkZero(float $value, float $epsilon = self::EPSILON)
    {
        return abs($value) < $epsilon ? 0.0 : $value;
    }

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

    /**
     * Annual Equivalent Rate (AER) of an annual percentage rate (APR).
     * The effective yearly rate of an annual percentage rate when the
     * annual percentage rate is compounded periodically within the year.
     *
     * The formula:
     * https://en.wikipedia.org/wiki/Effective_interest_rate
     *
     *        /     i \ ᴺ
     * AER = | 1 +  -  |  - 1
     *        \     n /
     *
     * Examples:
     * The AER of APR 3.5% interest compounded monthly.
     *   aer(0.035, 12)
     *
     * @param  float $nominal
     * @param  int $periods
     *
     * @return float
     */
    public static function aer(float $nominal, int $periods): float
    {
        if ($periods == 1) {
            return $nominal;
        }
        return pow(1 + ($nominal / $periods), $periods) - 1;
    }

    /**
     * Future value for a loan or anuity with compound interest.
     *
     * Same as the =FV() function in most spreadsheet software.
     *
     * The basic future-value formula derivation:
     * https://en.wikipedia.org/wiki/Future_value
     *
     *                   PMT*((1+r)ᴺ - 1)
     * FV = -PV*(1+r)ᴺ - ----------------
     *                          r
     *
     * The (1+r*when) factor adjusts the payment to the beginning or end
     * of the period. In the common case of a payment at the end of a period,
     * the factor is 1 and reduces to the formula above.
     *
     * Examples:
     * The future value in 5 years on a 30-year fixed mortgage note of $265000
     * at 3.5% interest paid at the end of every month. This is how much loan
     * principle would be outstanding:
     *   fv(0.035/12, 5*12, 1189.97, -265000, false)
     *
     * The present_value is negative indicating money borrowed for the mortgage,
     * whereas payment is positive, indicating money that will be paid to the
     * mortgage.
     *
     * @param  float $rate
     * @param  int   $periods
     * @param  float $payment
     * @param  float $present_value
     * @param  bool  $beginning adjust the payment to the beginning or end of the period
     *
     * @return float
     */
    public static function fv(float $rate, int $periods, float $payment, float $present_value, bool $beginning = false): float
    {
        $when = $beginning ? 1 : 0;

        if ($rate == 0) {
            $fv = - ($present_value + ($payment * $periods));
            return self::checkZero($fv);
        }

        $initial = 1 + ($rate * $when);
        $compound = pow(1 + $rate, $periods);
        $fv = - (($present_value * $compound) + (($payment * $initial * ($compound - 1)) / $rate));
        return self::checkZero($fv);
    }

    /**
     * Present value for a loan or anuity with compound interest.
     *
     * Same as the =PV() function in most spreadsheet software.
     *
     * The basic present-value formula derivation:
     * https://en.wikipedia.org/wiki/Present_value
     *
     *            PMT*((1+r)ᴺ - 1)
     * PV = -FV - ----------------
     *                   r
     *      ---------------------
     *             (1 + r)ᴺ
     *
     * The (1+r*when) factor adjusts the payment to the beginning or end
     * of the period. In the common case of a payment at the end of a period,
     * the factor is 1 and reduces to the formula above.
     *
     * Examples:
     * The present value of a band's $1000 face value paid in 5 year's time
     * with a constant discount rate of 3.5% compounded monthly:
     *   pv(0.035/12, 5*12, 0, -1000, false)
     *
     * The present value of a $1000 5-year bond that pays a fixed 7% ($70)
     * coupon at the end of each year with a discount rate of 5%:
     *   pv(0.5, 5, -70, -1000, false)
     *
     * The payment and future_value is negative indicating money paid out.
     *
     * @param  float $rate
     * @param  int   $periods
     * @param  float $payment
     * @param  float $future_value
     * @param  bool  $beginning adjust the payment to the beginning or end of the period
     *
     * @return float
     */
    public static function pv(float $rate, int $periods, float $payment, float $future_value, bool $beginning = false): float
    {
        $when = $beginning ? 1 : 0;

        if ($rate == 0) {
            $pv = -$future_value - ($payment * $periods);
            return self::checkZero($pv);
        }

        $initial = 1 + ($rate * $when);
        $compound = pow(1 + $rate, $periods);
        $pv = (-$future_value - (($payment * $initial * ($compound - 1)) / $rate)) / $compound;
        return self::checkZero($pv);
    }
}
