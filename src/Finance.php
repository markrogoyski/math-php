<?php
namespace MathPHP;

/**
  * General reference on financial functions and formulas:
  * http://docs.oasis-open.org/office/v1.2/os/OpenDocument-v1.2-os-part2.html#__RefHeading__1018228_715980110
  */
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
     * Number of payment periods of an annuity.
     *
     * @Param  float $rate
     * @Param  float $payment
     * @Param  float $present_value
     * @Param  float $future_value
     * @param  bool  $beginning adjust the payment to the beginning or end of the period
     *
     * @return float
     */
    public static function periods(float $rate, float $payment, float $present_value, float $future_value, bool $beginning = false): float
    {
        $when = $beginning ? 1 : 0;

        if ($rate == 0) {
            return - ($present_value + $future_value) / $payment;
        }
    }

    /**
     * Annual Equivalent Rate (AER) of an annual percentage rate (APR).
     * The effective yearly rate of an annual percentage rate when the
     * annual percentage rate is compounded periodically within the year.
     *
     * Same as the =EFFECT() function in most spreadsheet software.
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
     * Annual Nominal Rate of an annual effective rate (AER).
     * The nominal yearly rate of an annual effective rate when the
     * annual effective rate is compounded periodically within the year.
     *
     * Same as the =NOMINAL() function in most spreadsheet software.
     *
     * See:
     * https://en.wikipedia.org/wiki/Nominal_interest_rate
     *
     *           /          1/N    \
     * NOMINAL = | (AER + 1)    -1 | * N
     *           \                 /
     *
     * Examples:
     * The nominal rate of AER 3.557% interest compounded monthly.
     *   nominal(0.03557, 12)
     *
     * @param  float $aer
     * @param  int $periods
     *
     * @return float
     */
    public static function nominal(float $aer, int $periods): float
    {
        if ($periods == 1) {
            return $aer;
        }

        return (pow($aer + 1, 1/$periods) - 1) * $periods;
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
            $fv = -($present_value + ($payment * $periods));
            return self::checkZero($fv);
        }

        $initial  = 1 + ($rate * $when);
        $compound = pow(1 + $rate, $periods);
        $fv       = - (($present_value * $compound) + (($payment * $initial * ($compound - 1)) / $rate));

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

        $initial  = 1 + ($rate * $when);
        $compound = pow(1 + $rate, $periods);
        $pv       = (-$future_value - (($payment * $initial * ($compound - 1)) / $rate)) / $compound;

        return self::checkZero($pv);
    }

    /**
     * Net present value of cash flows. Cash flows are periodic starting
     * from an initial time and with a uniform discount rate.
     *
     * Similar to the =NPV() function in most spreadsheet software, except
     * the initial (usually negative) cash flow at time 0 is given as the
     * first element of the array rather than subtracted. For example,
     *   spreadsheet: =NPV(0.01, 100, 200, 300, 400) - 1000
     * is done as
     *   MathPHP::npv(0.01, [-1000, 100, 200, 300, 400])
     *
     * The basic net-present-value formula derivation:
     * https://en.wikipedia.org/wiki/Net_present_value
     *
     *  n      Rt
     *  Σ   --------
     * t=0  (1 / r)ᵗ
     *
     * Examples:
     * The net present value of 5 yearly cash flows after an initial $1000
     * investment with a 3% discount rate:
     *  npv(0.03, [-1000, 100, 500, 300, 700, 700])
     *
     * @param  float $rate
     * @param  array $values
     *
     * @return float
     */
    public static function npv(float $rate, array $values): float
    {
        $result = 0.0;

        for ($i = 0; $i < count($values); ++$i) {
            $result += $values[$i] / (1 + $rate)**$i;
        }

        return $result;
    }

    /**
     * Interest rate per period of an Annuity.
     *
     * Same as the =RATE() formula in most spreadsheet software.
     *
     * The basic rate formula derivation is to solve for the future value
     * taking into account the present value:
     * https://en.wikipedia.org/wiki/Future_value
     *
     *                        ((1+r)ᴺ - 1)
     * FV + PV*(1+r)ᴺ + PMT * ------------ = 0
     *                             r
     * The (1+r*when) factor adjusts the payment to the beginning or end
     * of the period. In the common case of a payment at the end of a period,
     * the factor is 1 and reduces to the formula above.
     *
     * Not all solutions for the rate have real-value solutions or converge.
     * In these cases, NAN is returned.
     *
     * @param  float $periods
     * @param  float $payment
     * @param  float $present_value
     * @param  float $future_value
     * @param  bool  $beginning
     * @param  float $initial_guess
     *
     * @return float
     */
    public static function rate(float $periods, float $payment, float $present_value, float $future_value, bool $beginning = false, float $initial_guess = 0.1): float
    {
        $when = $beginning ? 1 : 0;

        $func = function ($x, $periods, $payment, $present_value, $future_value, $when) {
            return $future_value + $present_value*(1+$x)**$periods + $payment*(1+$x*$when)/$x * ((1+$x)**$periods - 1);
        };

        return self::checkZero(NumericalAnalysis\RootFinding\NewtonsMethod::solve($func, [$initial_guess, $periods, $payment, $present_value, $future_value, $when], 0, self::EPSILON, 0));
    }

    /**
     * Internal rate of return.
     * Periodic rate of return that would provide a net-present value (NPV) of 0.
     *
     * Same as =IRR formula in most spreadshet software.
     *
     * Reference:
     * https://en.wikipedia.org/wiki/Internal_rate_of_return
     *
     * Examples:
     * The rate of return of an initial investment of $100 with returns
     * of $50, $40, and $30:
     *  irr([-100, 50, 40, 30])
     *
     * Solves for NPV=0 using Newton's Method.
     * TODO: Use eigenvalues to find the roots of a characteristic polynomial.
     * This will allow finding all solutions and eliminate the need of the initial_guess.
     *
     * @param  array $values
     * @param  float $initial_guess
     *
     * @return float
     */
    public static function irr(array $values, float $initial_guess = 0.1): float
    {
        $func = function ($x, $values) {
            return Finance::npv($x, $values);
        };

        return self::checkZero(NumericalAnalysis\RootFinding\NewtonsMethod::solve($func, [$initial_guess, $values], 0, self::EPSILON, 0));
    }

    /**
     * Modified internal rate of return.
     * Rate of return that discounts inflows (invetments) at the financing rate,
     * and reinvests outflows with an expected rate of return.
     *
     * Same as =MIRR formula in most spreadshet software.
     *
     * Reference:
     * https://en.wikipedia.org/wiki/Modified_internal_rate_of_return
     *
     * Examples:
     * The rate of return of an initial investment of $100 at 5% financing
     * with returns of $50, $40, and $30 reinvested at 10%:
     *  mirr([-100, 50, 40, 30], 0.05, 0.10)
     *
     * @param  array $values
     * @param  float $initial_guess
     *
     * @return float
     */
    public static function mirr(array $values, float $finance_rate, float $reinvestment_rate): float
    {
        $inflows = array();
        $outflows = array();
        for ($i = 0; $i < sizeof($values); $i++) {
            if ($values[$i] >= 0) {
                $inflows[] = 0;
                $outflows[] = $values[$i];
            } else {
                $inflows[] = $values[$i];
                $outflows[] = 0;
            }
        }

        $nonzero = function ($x) {
            return $x != 0;
        };

        if (sizeof(array_filter($inflows, $nonzero)) == 0 || sizeof(array_filter($outflows, $nonzero)) == 0) {
            return NAN;
        }

        $root = sizeof($values) - 1;
        $pv_outflows = self::npv($reinvestment_rate, $outflows);
        $fv_outflows = self::fv($reinvestment_rate, $root, 0, -$pv_outflows);
        $pv_inflows = self::npv($finance_rate, $inflows);
        return self::checkZero(pow($fv_outflows / -$pv_inflows, 1/$root) - 1);
    }
}
