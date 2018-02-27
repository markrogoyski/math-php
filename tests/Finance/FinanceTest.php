<?php
namespace MathPHP\Tests\Finance;

use MathPHP\Finance;

class FinanceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForcheckZero
     */
    public function testcheckZero(float $value, float $result)
    {
        $reflection = new \ReflectionClass('MathPHP\Finance');
        $method = $reflection->getMethod('checkZero');
        $method->setAccessible(true);
        $this->assertEquals($result, $method->invokeArgs(null, [$value]));
    }

    /**
     * @return array
     */
    public function dataProviderForcheckZero(): array
    {
        return [
            [0.0, 0.0],
            [0.1, 0.1],
            [0.01, 0.01],
            [0.001, 0.001],
            [0.0001, 0.0001],
            [0.00001, 0.00001],
            [0.000001, 0.000001],
            [0.0000001, 0.0],
            [0.00000001, 0.0],
            [0.000000001, 0.0],
            [0.0000000001, 0.0],
            [Finance::EPSILON, Finance::EPSILON],
            [Finance::EPSILON / 2, 0.0],
            [1.0, 1.0],
            [10.0, 10.0],
            [1e8, 1e8],
            [1e9, 1e9],
        ];
    }

    /**
     * @dataProvider dataProviderForPmt
     */
    public function testPmt(float $rate, int $periods, float $pv, float $fv, bool $beginning, float $pmt)
    {
        $this->assertEquals($pmt, Finance::pmt($rate, $periods, $pv, $fv, $beginning), '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForPmt(): array
    {
        return [
            [0.0, 1, 0, 0, false, 0.0],
            [0.0, 1, 1, 0, false, -1.0],
            [0.0, 1, -1, 0, false, 1.0],
            [0.0, 1, 1, 0, true, -1.0],
            [0.0, 1, -1, 0, true, 1.0],
            [0.0, 2, 1, 0, false, -0.5],
            [0.0, 2, -1, 0, false, 0.5],
            [0.0, 2, 1, 0, true, -0.5],
            [0.0, 2, -1, 0, true, 0.5],
            [0.05, 30, 250000, 0, false, -16262.858770069148],
            [0.05, 30, -250000, 0, false, 16262.858770069148],
            [0.05, 30, 250000, 0, true, -15488.436923875368],
            [0.05, 30, -250000, 0, true, 15488.436923875368],
            [0.04/12, 12*30, 85000, 0, false, -405.80300114563494],
            [0.04/12, 12*30, -85000, 0, false, 405.80300114563494],
            [0.04/12, 12*30, 85000, 0, true, -404.45481841757629],
            [0.04/12, 12*30, -85000, 0, true, 404.45481841757629],
            [0.035/12, 12*30, 475000, 0, false, -2132.9622670919189],
            [0.035/12, 12*30, -475000, 0, false, 2132.9622670919189],
            [0.035/12, 12*30, 475000, 0, true, -2126.7592193687524],
            [0.035/12, 12*30, -475000, 0, true, 2126.7592193687524],
            [0.035/12, 12*30, 475000, 100000, false, -2290.3402882340679],
            [0.035/12, 12*30, -475000, -100000, false, 2290.3402882340679],
            [0.035/12, 12*30, 475000, 100000, true, -2283.6795561951658],
            [0.035/12, 12*30, -475000, -100000, true, 2283.6795561951658],
            [0.10/4, 5*4, 0, 50000, false, -1957.3564367237279],
            [0.10/4, 5*4, 0, -50000, false, 1957.3564367237279],
            [0.10/4, 5*4, 0, 50000, true, -1909.6160358280276],
            [0.10/4, 5*4, 0, -50000, true, 1909.6160358280276],
            [0.035/12, 30*12, 265000, 0, false, -1189.9684226933862],
            [0.035/12, 5*12, 265000, 265000/2, false, -6844.7602923435943],
            [0.01/52, 3*52, -1500, 10000, false, -53.390735324685636],
            [0.04/4, 20*4, 1000000, 0, false, -18218.850112732187],
        ];
    }

    /**
     * @dataProvider dataProviderForIpmt
     */
    public function testIPMT(float $rate, int $period, int $periods, float $pv, float $fv, bool $beginning, float $ipmt)
    {
        $result = Finance::ipmt($rate, $period, $periods, $pv, $fv, $beginning);
        $this->assertEquals($ipmt, $result, '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForIpmt(): array
    {
        return [
            [0.0, 1, 1, 0, 0, false, 0.0],
            [0.0, 1, 2, 0, 0, false, 0.0],
            [0.0, 2, 2, 0, 0, false, 0.0],
            [0.0, 1, 1, 1, 0, false, 0.0],
            [0.0, 1, 2, 1, 0, false, 0.0],
            [0.0, 2, 2, 1, 0, false, 0.0],
            [0.05, 1, 1, 1, 0, false, -0.05],
            [0.05, 1, 2, 1, 0, false, -0.05],
            [0.05, 2, 2, 1, 0, false, -0.025609756097560967],
            [0.05, 1, 3, 10, 0, false, -0.5],
            [0.05, 2, 3, 10, 0, false, -0.34139571768437743],
            [0.05, 3, 3, 10, 0, false, -0.17486122125297401],
            [0.035/12, 1, 360, 475000, 0, false, -1385.4166666666667],
            [0.035/12, 2, 360, 475000, 0, false, -1383.2363253320932],
            [0.035/12, 3, 360, 475000, 0, false, -1381.0496246686268],
            [0.035/12, 358, 360, 475000, 0, false, -18.555076810964287],
            [0.035/12, 359, 360, 475000, 0, false, -12.388055839311468],
            [0.035/12, 360, 360, 475000, 0, false, -6.203047723157991],
            [0.0, 1, 1, 0, 0, true, 0.0],
            [0.0, 1, 2, 0, 0, true, 0.0],
            [0.0, 2, 2, 0, 0, true, 0.0],
            [0.0, 1, 1, 1, 0, true, 0.0],
            [0.0, 1, 2, 1, 0, true, 0.0],
            [0.0, 2, 2, 1, 0, true, 0.0],
            [0.05, 1, 1, 1, 0, true, 0.0],
            [0.05, 1, 2, 1, 0, true, 0.0],
            [0.05, 2, 2, 1, 0, true, -0.024390243902439036],
            [0.05, 1, 3, 10, 0, true, 0.0],
            [0.05, 2, 3, 10, 0, true, -0.32513877874702635],
            [0.05, 3, 3, 10, 0, true, -0.16653449643140378],
            [0.035/12, 1, 360, 475000, 0, true, 0.0],
            [0.035/12, 2, 360, 475000, 0, true, -1379.213618943508],
            [0.035/12, 3, 360, 475000, 0, true, -1377.0332776089344],
            [0.035/12, 358, 360, 475000, 0, true, -18.50111522489313],
            [0.035/12, 359, 360, 475000, 0, true, -12.352029087806763],
            [0.035/12, 360, 360, 475000, 0, true, -6.1850081161539432],
        ];
    }

    /**
     * @dataProvider dataProviderForIpmtNan
     */
    public function testIpmtNan(float $rate, int $period, int $periods, float $pv, float $fv, bool $beginning)
    {
        $result = Finance::ipmt($rate, $period, $periods, $pv, $fv, $beginning);
        $this->assertNan($result);
    }

    /**
     * @return array
     */
    public function dataProviderForIpmtNan(): array
    {
        return [
            [0.0, 0, 1, 0, 0, false, NAN],
            [0.0, 2, 1, 0, 0, false, NAN],
            [0.0, 0, 2, 0, 0, false, NAN],
            [0.0, 3, 2, 0, 0, false, NAN],
            [0.0, 0, 1, 1, 0, false, NAN],
            [0.0, 2, 1, 1, 0, false, NAN],
            [0.0, 0, 2, 1, 0, false, NAN],
            [0.0, 3, 2, 1, 0, false, NAN],
            [0.05, 0, 3, 10, 0, false, NAN],
            [0.0, 0, 1, 0, 0, true, NAN],
            [0.0, 2, 1, 0, 0, true, NAN],
            [0.0, 0, 2, 0, 0, true, NAN],
            [0.0, 3, 2, 0, 0, true, NAN],
            [0.0, 0, 1, 1, 0, true, NAN],
            [0.0, 2, 1, 1, 0, true, NAN],
            [0.0, 0, 2, 1, 0, true, NAN],
            [0.0, 3, 2, 1, 0, true, NAN],
            [0.05, 0, 3, 10, 0, true, NAN],
        ];
    }

    /**
     * @dataProvider dataProviderForPpmt
     */
    public function testPPMT(float $rate, int $period, int $periods, float $pv, float $fv, bool $beginning, float $ppmt)
    {
        $result = Finance::ppmt($rate, $period, $periods, $pv, $fv, $beginning);
        $this->assertEquals($ppmt, $result, '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForPpmt(): array
    {
        return [
            [0.0, 1, 1, 0, 0, false, 0.0],
            [0.0, 1, 2, 0, 0, false, 0.0],
            [0.0, 2, 2, 0, 0, false, 0.0],
            [0.0, 1, 1, 1, 0, false, -1.0],
            [0.0, 1, 2, 1, 0, false, -0.5],
            [0.0, 2, 2, 1, 0, false, -0.5],
            [0.05, 1, 1, 1, 0, false, -1.0],
            [0.05, 1, 2, 1, 0, false, -0.48780487804878031],
            [0.05, 2, 2, 1, 0, false, -0.5121951219512193],
            [0.05, 1, 3, 10, 0, false, -3.172085646312448],
            [0.05, 2, 3, 10, 0, false, -3.3306899286280705],
            [0.05, 3, 3, 10, 0, false, -3.497224425059474],
            [0.035/12, 1, 360, 475000, 0, false, -747.54560042525213],
            [0.035/12, 2, 360, 475000, 0, false, -749.72594175982567],
            [0.035/12, 3, 360, 475000, 0, false, -751.91264242329203],
            [0.035/12, 358, 360, 475000, 0, false, -2114.4071902809546],
            [0.035/12, 359, 360, 475000, 0, false, -2120.5742112526073],
            [0.035/12, 360, 360, 475000, 0, false, -2126.759219368761],
            [0.0, 1, 1, 0, 0, true, 0.0],
            [0.0, 1, 2, 0, 0, true, 0.0],
            [0.0, 2, 2, 0, 0, true, 0.0],
            [0.0, 1, 1, 1, 0, true, -1.0],
            [0.0, 1, 2, 1, 0, true, -0.5],
            [0.0, 2, 2, 1, 0, true, -0.5],
            [0.05, 1, 1, 1, 0, true, -1.0],
            [0.05, 1, 2, 1, 0, true, -0.5121951219512193],
            [0.05, 2, 2, 1, 0, true, -0.48780487804878025],
            [0.05, 1, 3, 10, 0, true, -3.497224425059474],
            [0.05, 2, 3, 10, 0, true, -3.1720856463124476],
            [0.05, 3, 3, 10, 0, true, -3.33068992862807],
            [0.035/12, 1, 360, 475000, 0, true, -2126.7592193687524],
            [0.035/12, 2, 360, 475000, 0, true, -747.54560042525168],
            [0.035/12, 3, 360, 475000, 0, true, -749.72594175982522],
            [0.035/12, 358, 360, 475000, 0, true, -2108.2581041438666],
            [0.035/12, 359, 360, 475000, 0, true, -2114.4071902809528],
            [0.035/12, 360, 360, 475000, 0, true, -2120.5742112526059],
        ];
    }

    /**
     * @dataProvider dataProviderForPpmtNan
     */
    public function testPpmtNan(float $rate, int $period, int $periods, float $pv, float $fv, bool $beginning)
    {
        $result = Finance::ppmt($rate, $period, $periods, $pv, $fv, $beginning);
        $this->assertNan($result);
    }

    /**
     * @return array
     */
    public function dataProviderForPpmtNan(): array
    {
        return [
            [0.0, 0, 1, 0, 0, false, NAN],
            [0.0, 2, 1, 0, 0, false, NAN],
            [0.0, 0, 2, 0, 0, false, NAN],
            [0.0, 3, 2, 0, 0, false, NAN],
            [0.0, 0, 1, 1, 0, false, NAN],
            [0.0, 2, 1, 1, 0, false, NAN],
            [0.0, 0, 2, 1, 0, false, NAN],
            [0.0, 3, 2, 1, 0, false, NAN],
            [0.05, 0, 3, 10, 0, false, NAN],
            [0.0, 0, 1, 0, 0, true, NAN],
            [0.0, 2, 1, 0, 0, true, NAN],
            [0.0, 0, 2, 0, 0, true, NAN],
            [0.0, 3, 2, 0, 0, true, NAN],
            [0.0, 0, 1, 1, 0, true, NAN],
            [0.0, 2, 1, 1, 0, true, NAN],
            [0.0, 0, 2, 1, 0, true, NAN],
            [0.0, 3, 2, 1, 0, true, NAN],
            [0.05, 0, 3, 10, 0, true, NAN],
        ];
    }

    /**
     * @dataProvider dataProviderForPeriods
     */
    public function testPeriods(float $rate, float $payment, float $pv, float $fv, bool $beginning, float $periods)
    {
        $result = Finance::periods($rate, $payment, $pv, $fv, $beginning);
        $this->assertEquals($periods, $result, '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForPeriods(): array
    {
        return [
            [0.0, 1, 0, 0, false, 0.0],
            [0.0, 1, 1, 0, false, -1.0],
            [0.0, 1, -1, 0, false, 1.0],
            [0.0, 1, 0, 1, false, -1.0],
            [0.0, 1, 0, -1, false, 1.0],
            [0.0, 1, 1, 1, false, -2.0],
            [0.0, 1, -1, 1, false, 0.0],
            [0.0, 1, 1, -1, false, 0.0],
            [0.0, 1, -1, -1, false, 2.0],
            [0.0, -1, 0, 0, false, 0.0],
            [0.0, -1, 1, 0, false, 1.0],
            // numpy 1.12.0b1 gives 1.0 for this, whereas spreadsheet software gives 1.0.
            // The interpretation is that a payment of $1 is made to an annuity that owes
            // $1. To end up with $0, the payment will need to be reversed once.
            [0.0, -1, -1, 0, false, -1.0],
            [0.0, -1, 0, 1, false, 1.0],
            [0.0, -1, 0, -1, false, -1.0],
            [0.0, -1, 1, 1, false, 2.0],
            [0.0, -1, -1, 1, false, 0.0],
            [0.0, -1, 1, -1, false, 0.0],
            [0.0, -1, -1, -1, false, -2.0],
            [0.01, -100, 5000, 0, false, 69.660716893574829],
            [0.001, -100, 5000, 0, false, 51.318936762444572],
            [0.0001, -100, 5000, 0, false, 50.127924464590137],
            [0.00001, -100, 5000, 0, false, 50.012754230013776],
            [0.000001, -100, 5000, 0, false, 50.001275046275666],
            [0.0, -100, 5000, 0, false, 50.0],
            [0.035/12.0, -2132, 475000, 0, false, 360.28732845118219],
            [0.035/12.0, -2132.9622670919111, 475000, 0, false, 360.0],
            [0.035/12.0, -2126.7592193687524, 475000, 0, false, 361.86102291347339],
            [0.035/12.0, -2126.7592193687524, 475000, 0, true, 360.0],
            [0.05, -1000.0, 0, 19600, false, 14.000708059400562],
            [0.05, -1000.0, 0, 19600, true, 13.511855106593261],
        ];
    }

    /**
     * @dataProvider dataProviderForPeriodsNan
     */
    public function testPeriodsNan(float $rate, float $payment, float $pv, float $fv, bool $beginning)
    {
        $result = Finance::periods($rate, $payment, $pv, $fv, $beginning);
        $this->assertNan($result);
    }

    /**
     * @return array
     */
    public function dataProviderForPeriodsNan(): array
    {
        return [
            [0.1, -100, 5000, 0, false, NAN],
        ];
    }

    /**
     * @dataProvider dataProviderForAer
     */
    public function testAer(float$nominal, int $periods, float $rate)
    {
        $this->assertEquals($rate, Finance::aer($nominal, $periods), '', Finance::EPSILON);
    }

    public function dataProviderForAer(): array
    {
        return [
            [0.0, 1, 0.0],
            [0.035, 12, 0.035566952945970565],
            [0.06, 12, 0.061677811864497611],
            [0.01, 1, 0.01],
            [0.01, 2, 0.010024999999999729],
            [0.01, 4, 0.010037562539062295],
            [0.01, 12, 0.010045960887180572],
            [0.01, 365, 0.010050028723672],
            [0.05, 1, 0.05],
            [0.05, 2, 0.05062499999999992],
            [0.05, 4, 0.050945336914062445],
            [0.05, 12, 0.05116189788173342],
            [0.05, 365, 0.051267496467422902],
            [0.10, 1, 0.1],
            [0.10, 2, 0.1025],
            [0.10, 4, 0.10381289062499977],
            [0.10, 12, 0.10471306744129683],
            [0.10, 365, 0.10515578161622718],
            [0.15, 1, 0.15],
            [0.15, 2, 0.1556249999999999],
            [0.15, 4, 0.15865041503906308],
            [0.15, 12, 0.16075451772299854],
            [0.15, 365, 0.16179844312826397],
            [0.20, 1, 0.2],
            [0.20, 2, 0.21000000000000019],
            [0.20, 4, 0.21550625000000001],
            [0.20, 12, 0.21939108490523185],
            [0.20, 365, 0.22133585825175062],
            [0.30, 1, 0.3],
            [0.30, 2, 0.32249999999999979],
            [0.30, 4, 0.33546914062499988],
            [0.30, 12, 0.34488882424629752],
            [0.30, 365, 0.34969248800768127],
            [0.40, 1, 0.4],
            [0.40, 2, 0.43999999999999995],
            [0.40, 4, 0.4641],
            [0.40, 12, 0.48212648965463845],
            [0.40, 365, 0.49149799683290096],
            [0.50, 1, 0.50],
            [0.50, 2, 0.5625],
            [0.50, 4, 0.601806640625],
            [0.50, 12, 0.63209413272292592],
            [0.50, 365, 0.64815725173913452],
            [1.0, 1, 1.0],
            [1.0, 2, 1.25],
        ];
    }

    /**
     * @dataProvider dataProviderForNominal
     */
    public function testNominal(float$aer, int $periods, float $rate)
    {
        $this->assertEquals($rate, Finance::nominal($aer, $periods), '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForNominal(): array
    {
        return [
            [0.0, 1, 0.0],
            [0.035, 12, 0.034450784628919706],
            [0.06, 12, 0.058410606784116581],
            [0.01, 1, 0.01],
            [0.01, 2, 0.0099751242241779003],
            [0.01, 4, 0.0099627172572844813],
            [0.01, 12, 0.0099544573721539464],
            [0.01, 365, 0.0099504664832628098],
            [0.05, 1, 0.05],
            [0.05, 2, 0.049390153191919861],
            [0.05, 4, 0.04908893771615741],
            [0.05, 12, 0.048889485403780242],
            [0.05, 365, 0.048793425246426159],
            [0.10, 1, 0.1],
            [0.10, 2, 0.097617696340303262],
            [0.10, 4, 0.096454756337780445],
            [0.10, 12, 0.095689685146845171],
            [0.10, 365, 0.095322624764762054],
            [0.15, 1, 0.15],
            [0.15, 2, 0.14476105895272173],
            [0.15, 4, 0.14223230536648845],
            [0.15, 12, 0.14057900303824056],
            [0.15, 365, 0.1397887038737311],
            [0.20, 1, 0.2],
            [0.20, 2, 0.1908902300206643],
            [0.20, 4, 0.18654055756842247],
            [0.20, 12, 0.18371364599677431],
            [0.20, 365, 0.18236710019882918],
            [0.30, 1, 0.3],
            [0.30, 2, 0.2803508501982761],
            [0.30, 4, 0.27115988948976355],
            [0.30, 12, 0.26525340712339052],
            [0.30, 365, 0.26245858159523849],
            [0.40, 1, 0.4],
            [0.40, 2, 0.36643191323984636],
            [0.40, 4, 0.35102922374910861],
            [0.40, 12, 0.34123386871633521],
            [0.40, 365, 0.33662737136420096],
            [0.50, 1, 0.50],
            [0.50, 2, 0.44948974278317788],
            [0.50, 4, 0.42672767880128681],
            [0.50, 12, 0.41239299758299897],
            [0.50, 365, 0.40569039967917164],
            [1.0, 1, 1.0],
            [1.0, 2, 0.82842712474619029],
        ];
    }

    /**
     * @dataProvider dataProviderForFv
     */
    public function testFv(float $rate, int $periods, float $pmt, float $pv, bool $beginning, float $fv)
    {
        $this->assertEquals($fv, Finance::fv($rate, $periods, $pmt, $pv, $beginning), '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForFv(): array
    {
        return [
            [0.0, 0, 0, 0, false, 0.0],
            [0.1, 0, 0, 0, false, 0.0],
            [0.0, 1, 0, 0, false, 0.0],
            [0.0, 0, 1, 0, false, 0.0],
            [0.0, 0, 0, 1, false, -1.0],
            [0.0, 0, 0, -1, false, 1.0],
            [0.0, 0, 1, 1, false, -1.0],
            [0.0, 0, -1, -1, false, 1.0],
            [0.0, 0, -1, 1, false, -1.0],
            [0.0, 0, 1, -1, false, 1.0],
            [0.0, 1, 1, 1, false, -2.0],
            [0.0, 1, -1, 1, false, 0.0],
            [0.0, 1, 1, -1, false, 0.0],
            [0.0, 1, -1, -1, false, 2.0],
            [0.1, 0, 0, 0, false, 0.0],
            [0.1, 1, 0, 0, false, 0.0],
            [0.1, 0, 1, 0, false, 0.0],
            [0.1, 0, 0, 1, false, -1.0],
            [0.1, 1, 1, 0, false, -1.0],
            [0.1, 1, 0, 1, false, -1.1],
            [0.1, 1, 1, 1, false, -2.1],
            [0.0, 0, 0, 0, true, 0.0],
            [0.1, 0, 0, 0, true, 0.0],
            [0.0, 1, 0, 0, true, 0.0],
            [0.0, 0, 1, 0, true, 0.0],
            [0.0, 0, 0, 1, true, -1.0],
            [0.0, 0, 0, -1, true, 1.0],
            [0.0, 0, 1, 1, true, -1.0],
            [0.0, 0, -1, -1, true, 1.0],
            [0.0, 0, -1, 1, true, -1.0],
            [0.0, 0, 1, -1, true, 1.0],
            [0.0, 1, 1, 1, true, -2.0],
            [0.0, 1, -1, 1, true, 0.0],
            [0.0, 1, 1, -1, true, 0.0],
            [0.0, 1, -1, -1, true, 2.0],
            [0.1, 0, 0, 0, true, 0.0],
            [0.1, 1, 0, 0, true, 0.0],
            [0.1, 0, 1, 0, true, 0.0],
            [0.1, 0, 0, 1, true, -1.0],
            [0.1, 1, 1, 0, true, -1.1],
            [0.1, 1, 0, 1, true, -1.1],
            [0.1, 1, 1, 1, true, -2.2],
            [0.05/12, 120, -100, -100, false, 15692.928894335892],
            [0.035/12, 360, 2132.9622670919189, 475000, false, -2710622.8069359586],
            [0.035/12, 360, -2132.9622670919189, 475000, false, 0.0],
            [0.035/12, 360, 2132.9622670919189, -475000, false, 0.0],
            [0.035/12, 360, -2132.9622670919189, -475000, false, 2710622.8069359586],
            [0.035/12, 360, 2132.9622670919189, 475000, true, -2714575.798529407],
            [0.035/12, 360, -2132.9622670919189, 475000, true, 3952.9915934484452],
            [0.035/12, 360, 2132.9622670919189, -475000, true, -3952.9915934484452],
            [0.035/12, 360, -2132.9622670919189, -475000, true, 2714575.798529407],
        ];
    }

    /**
     * @dataProvider dataProviderForPv
     */
    public function testPv(float $rate, int $periods, float $pmt, float $fv, bool $beginning, float $pv)
    {
        $this->assertEquals($pv, Finance::pv($rate, $periods, $pmt, $fv, $beginning), '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForPv(): array
    {
        return [
            [0.0, 0, 0, 0, false, 0.0],
            [0.1, 0, 0, 0, false, 0.0],
            [0.0, 1, 0, 0, false, 0.0],
            [0.0, 0, 1, 0, false, 0.0],
            [0.0, 0, 0, 1, false, -1.0],
            [0.0, 0, 0, -1, false, 1.0],
            [0.0, 0, 1, 1, false, -1.0],
            [0.0, 0, -1, -1, false, 1.0],
            [0.0, 0, -1, 1, false, -1.0],
            [0.0, 0, 1, -1, false, 1.0],
            [0.0, 1, 1, 1, false, -2.0],
            [0.0, 1, -1, 1, false, 0.0],
            [0.0, 1, 1, -1, false, 0.0],
            [0.0, 1, -1, -1, false, 2.0],
            [0.1, 0, 0, 0, false, 0.0],
            [0.1, 1, 0, 0, false, 0.0],
            [0.1, 0, 1, 0, false, 0.0],
            [0.1, 0, 0, 1, false, -1.0],
            [0.1, 1, 1, 0, false, -0.90909090909090984],
            [0.1, 1, 0, 1, false, -0.90909090909090984],
            [0.1, 1, 1, 1, false, -1.8181818181818188],
            [0.0, 0, 0, 0, true, 0.0],
            [0.1, 0, 0, 0, true, 0.0],
            [0.0, 1, 0, 0, true, 0.0],
            [0.0, 0, 1, 0, true, 0.0],
            [0.0, 0, 0, 1, true, -1.0],
            [0.0, 0, 0, -1, true, 1.0],
            [0.0, 0, 1, 1, true, -1.0],
            [0.0, 0, -1, -1, true, 1.0],
            [0.0, 0, -1, 1, true, -1.0],
            [0.0, 0, 1, -1, true, 1.0],
            [0.0, 1, 1, 1, true, -2.0],
            [0.0, 1, -1, 1, true, 0.0],
            [0.0, 1, 1, -1, true, 0.0],
            [0.0, 1, -1, -1, true, 2.0],
            [0.1, 0, 0, 0, true, 0.0],
            [0.1, 1, 0, 0, true, 0.0],
            [0.1, 0, 1, 0, true, 0.0],
            [0.1, 0, 0, 1, true, -1.0],
            [0.1, 1, 1, 0, true, -1.0],
            [0.1, 1, 0, 1, true, -0.90909090909090906],
            [0.1, 1, 1, 1, true, -1.9090909090909098],
            [0.035/12, 5*12, 0, -1000, false, 839.67086876847554],
            [0.035/12, 5*12, 0, -1000, true, 839.67086876847554],
            [0.05, 5, -70, -1000, false, 1086.5895334126164],
            [0.05, 5, -70, -1000, true, 1101.7427017598243],
            [0.035/12, 12*30, -2132.9622670919189, 0, false, 475000],
        ];
    }

    /**
     * @dataProvider dataProviderForNpv
     */
    public function testNpv(float $rate, array $values, float $npv)
    {
        $this->assertEquals($npv, Finance::npv($rate, $values), '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForNpv(): array
    {
        return [
            [0.0, [], 0.0],
            [0.0, [0.0], 0.0],
            [0.0, [0.0, 0.0], 0.0],
            [0.01, [0.0], 0.0],
            [0.0, [1.0], 1.0],
            [0.0, [1.0, 0.1], 1.1],
            [0.0, [1.0, 0.1, 0.5], 1.6],
            [0.0, [-1.0], -1.0],
            [0.0, [-1.0, -0.1], -1.1],
            [0.0, [-1.0, -0.1, -0.5], -1.6],
            [0.00, [-1.0, 1.0], 0.0],
            [0.01, [-1.0], -1.0],
            [0.01, [-1.0, 1.0], -0.0099009900990099098],
            [0.01, [-1.0, 1.1], 0.089108910891089188],
            [0.01, [-1000, 500, 500], -14.802470346044515],
            [0.01, [-1000, 500, 500, 500], 470.49260361777766],
            [0.01, [-1000, 100, 200, 300, 400], -29.361706957097013],
            [0.12, [-1000, 100, 200, 300, 400], -283.53420449812597],
            [0.12, [-1000, 100, 200, 300, 400, 500], 0.17922336117362647],
            [0.03, [-1000, 100, -500, 300, 700, 700], 126.09900448974433],
        ];
    }

    /**
     * @dataProvider dataProviderForRate
     */
    public function testRate(float $periods, float $payment, float $present_value, float $future_value, bool $beginning, float $initial_guess, float $rate)
    {
        $result = Finance::rate($periods, $payment, $present_value, $future_value, $beginning, $initial_guess, $rate);
        $this->assertEquals($rate, $result, '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForRate(): array
    {
        return [
            [1, 0.0, 1.0, 0.0, false, 0.1, -1.0],
            [1, 1.0, 1.0, 0.0, false, 0.1, -2.0],
            [1, -1.0, 2.0, 0.0, false, 0.1, -0.5],
            [1, -1.0, 0.0, 0.0, true, 0.1, -1.0],
            [1, 0.0, 1.0, 0.0, true, 0.1, -1.0],
            [1, 1.0, 1.0, 0.0, true, 0.1, -1.0],
            [1, -1.0, 2.0, 0.0, true, 0.1, -1.0],
            [1, -1.0, 0.0, 1.0, true, 0.1, 0.0],
            [2, -1.0, 0.0, 0.0, false, 0.1, -2.0],
            [2, 0.0, 1.0, 0.0, false, 0.1, -0.99973094574435628],
            [2, -1.0, 2.0, 0.0, false, 0.1, 0.0],
            [2, -1.0, 0.0, 1.0, false, 0.1, -1.0],
            [2, -1.0, 0.0, 0.0, true, 0.1, -1.0],
            [2, 0.0, 1.0, 0.0, true, 0.1, -0.99973094574435628],
            [2, 1.0, 1.0, 0.0, true, 0.1, -1.0],
            [2, -1.0, 2.0, 0.0, true, 0.1, 0.0],
            [2, -1.0, 0.0, 1.0, true, 0.1, -0.38196601125010515],
            [2, -1, 0, 0, false, 0.1, -2.0],
            [2, -1, 0, 1, false, 0.1, -1.0],
            [2, -1, 0, 2, false, 0.1, 0.0],
            [2, -1, 0, 3, false, 0.1, 1.0],
            [2, -1, 0, 0, true, 0.1, -1.0],
            [2, -1, 0, 1, true, 0.1, -0.38196601125010515],
            [2, -1, 0, 2, true, 0.1, 0.0],
            [2, -1, 0, 3, true, 0.1, 0.30277563773199473],
            [48, -200, 8000, 0.0, false, 0.1, 0.0077014724882025348],
            [360, -2132.96, 475000, 0.0, false, 0.1, 0.0029166595414678938],
        ];
    }

    /**
     * @dataProvider dataProviderForRateNan
     */
    public function testRateNan(float $periods, float $payment, float $present_value, float $future_value, bool $beginning, float $initial_guess, float $rate)
    {
        $result = Finance::rate($periods, $payment, $present_value, $future_value, $beginning, $initial_guess, $rate);
        $this->assertNan($result);
    }

    /**
     * @return array
     */
    public function dataProviderForRateNan(): array
    {
        return [
            [0, 0.0, 0.0, 0.0, false, 0.1, NAN],
            [1, 0.0, 0.0, 0.0, false, 0.1, NAN],
            [1, -1.0, 0.0, 0.0, false, 0.1, NAN],
            [1, 0.0, 0.0, 1.0, false, 0.1, NAN],
            [1, -1.0, 0.0, 1.0, false, 0.1, NAN],
            [1, 0.0, 0.0, 0.0, true, 0.1, NAN],
            [1, 0.0, 0.0, 1.0, true, 0.1, NAN],
            [2, 0.0, 0.0, 0.0, false, 0.1, NAN],
            [2, 0.0, 0.0, 1.0, false, 0.1, NAN],
            [2, 1.0, 1.0, 0.0, false, 0.1, NAN],
            [2, 0.0, 0.0, 0.0, true, 0.1, NAN],
            [2, 0.0, 0.0, 1.0, true, 0.1, NAN],
        ];
    }

    /**
     * @dataProvider dataProviderForIrr
     */
    public function testIrr(array $values, float $initial_guess, float $irr)
    {
        $result = Finance::irr($values, $initial_guess);
        $this->assertEquals($irr, $result, '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForIrr(): array
    {
        return [
            [[1, -1], 0.1, 0.0],
            [[-1, 1], 0.1, 0.0],
            [[-1, 2], 0.1, 1.0],
            [[-1, 3], 0.1, 2.0],
            [[-1, 1, 1], 0.1, 0.61803398875009197],
            [[-2.3, 2.3, 2.3], 0.1, 0.61803398875009197],
            [[2.3, -2.3, -2.3], 0.1, 0.61803398875009197],
            [[-2.4, 2.4, 2.4], 0.1, 0.61803398875009197],
            [[2.4, -2.4, -2.4], 0.1, 0.61803398875009197],
            [[-100, 100, 100], 0.1, 0.61803398875009197],
            [[100, -100, -100], 0.1, 0.61803398875009197],
            [[-100, 39, 59, 55, 20], 0.1, 0.28094842115996116],
            [[-100, 0, 0, 74], 0.1, -0.095495830348972563],
            [[-100, 100, 0, -7], 0.1, -0.083299666184932702],
            [[-100, 100, 0, 7], 0.1, 0.06205848562992955],
            [[-5, 10.5, 1, -8, 1], 0.1, 0.088598338527755019],
            [[5, -10.5, -1, 8, -1], 0.1, 0.088598338527755019],
            [[-123400, 36200, 54800, 48100], 0.1, 0.059616378567329452],
            [[-10, 21, -11], 0.1, 0.1],
            [[-10, 21, -11], 0.05, 0.1],
            [[-10, 21, -11], 0.01, 0.0],
            [[-10, 21, -11], 0.001, 0.0],
            [[-10, 21, -11], -0.001, 0.0],
            [[-1000000, 300000, 300000, 300000, 300000, 300000], 0.1, 0.15238237116630671],
            [[-1000000, 10000000, -10000000, 0, 0, 0], 0.1, 0.12701665379258315],
            [[-1000000, 10000000, -10000000, 0, 0, 0], 0.633, 0.12701665379258315],
        ];
    }

    /**
     * @dataProvider dataProviderForIrrNan
     */
    public function testIrrNan(array $values, float $initial_guess)
    {
        $result = Finance::irr($values, $initial_guess);
        $this->assertNan($result);
    }

    /**
     * @return array
     */
    public function dataProviderForIrrNan(): array
    {
        return [
            [[-1], 0.1, NAN],
            [[0], 0.1, NAN],
            [[1], 0.1, NAN],
            [[1, 0], 0.1, NAN],
            [[1, 1], 0.1, NAN],
            [[1, 2], 0.1, NAN],
            [[1, 3], 0.1, NAN],
            [[-1, -1], 0.1, NAN],
            [[-1, 0], 0.1, NAN],
            [[-1000000, 10000000, -10000000, 0, 0, 0], 0.634, NAN],
        ];
    }

    /**
     * @dataProvider dataProviderForMirr
     */
    public function testMirr(array $values, float $finance_rate, float $reinvestment_rate, float $mirr)
    {
        $result = Finance::mirr($values, $finance_rate, $reinvestment_rate);
        $this->assertEquals($mirr, $result, '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForMirr(): array
    {
        return [
            [[-1, 1], 0.1, 0.1, 0.0],
            [[-1, 2], 0.1, 0.1, 1.0],
            [[1, -1], 0.05, 0.07, 0.1235],
            [[-1000, -4000, 5000, 2000], 0.10, 0.12, 0.17908568603489283],
            [[-250000, 50000, 100000, 200000], 0.13, 0.13, 0.14658850347563979],
            [[-10000, 18000, -50000, 25000, 25000, 225000], 0.05, 0.1, 0.410571259576975271],
            [[-10000, 18000, -50000, 25000, 25000, 225000], 0.1, 0.05, 0.42417388160672798],
            [[-100000, 18000, -50000, 25000, 25000, 225000], 0.05, 0.1, 0.16288556821502476],
            [[-100000, 18000, -50000, 25000, 25000, 225000], 0.1, 0.05, 0.1630064271697238],
        ];
    }

    /**
     * @dataProvider dataProviderForMirrNan
     */
    public function testMirrNan(array $values, float $finance_rate, float $reinvestment_rate)
    {
        $result = Finance::mirr($values, $finance_rate, $reinvestment_rate);
        $this->assertNan($result);
    }

    /**
     * @return array
     */
    public function dataProviderForMirrNan(): array
    {
        return [
            [[], 0.1, 0.1, NAN],
            [[-1], 0.1, 0.1, NAN],
            [[-1, -2], 0.1, 0.1, NAN],
            [[1], 0.1, 0.1, NAN],
            [[1, 2], 0.1, 0.1, NAN],
        ];
    }

    /**
     * @dataProvider dataProviderForPayback
     */
    public function testPayback(array $values, float $rate, float $payback)
    {
        $result = Finance::payback($values, $rate);
        $this->assertEquals($payback, $result, '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForPayback(): array
    {
        return [
            [[], 0.0, 0.0],
            [[0], 0.0, 0.0],
            [[1], 0.0, 0.0],
            [[0, 0], 0.0, 0.0],
            [[1, 0], 0.0, 0.0],
            [[0, 1], 0.0, 0.0],
            [[1, 1], 0.0, 0.0],
            [[-1, 1], 0.0, 1],
            [[-1, 0, 1], 0.0, 2],
            [[-1, 1, 0], 0.0, 1],
            [[-1, 1, 1], 0.0, 1],
            [[-1, 1, 1], 0.01, 1.0101],
            [[-1, 1, 1], 0.10, 1.11],
            [[-1, 1, 1], 0.50, 1.75],
            [[-1, 1, 1], 0.60, 1.96],
            [[-1, 1, 1], 0.61803398, 1.99999998043464],
            [[-2, 1, 1], 0.0, 2],
            [[-2, 2, 1], 0.0, 1],
            [[-2, 0, 2], 0.0, 2],
            [[-2, 1, 2], 0.0, 1.5],
            [[-10, 7, 7], 0.0, 1.4285714285714286],
            [[-10, 5, -3, 5, 5], 0.0, 3.6],
            [[-10, 5, -5, 5, 5], 0.0, 4.0],
            [[-10, 5, -5, 5, 6, -1], 0.0, 3.8333333333333335],
            [[-10, 15, -7, 5, 6, -1], 0.0, 2.4],
            [[-10, 15, -7, 5, 6, -10, 1], 0.0, 6],
            [[-1000, 100, 200, 300, 400, 500], 0.0, 4],
            [[-1000, 100, 200, 300, 400, 500], 0.1, 4.7898],
            [[-2324000, 600000, 600000, 600000, 600000, 600000, 600000], 0.11, 5.3318794669369414],
        ];
    }

    /**
     * @dataProvider dataProviderForPaybackNan
     */
    public function testPaybackNan(array $values, float $rate)
    {
        $result = Finance::payback($values, $rate);
        $this->assertNan($result);
    }

    /**
     * @return array
     */
    public function dataProviderForPaybackNan(): array
    {
        return [
            [[-1], 0.0, NAN],
            [[-1, 0], 0.0, NAN],
            [[-1, 1, 1], 0.62, NAN],
            [[-1, 1, 1], 0.61803399, NAN],
            [[-1, 1, 1], 0.62, NAN],
            [[-1, 1, 1], 1.0, NAN],
            [[-1, 1, 1], 2.0, NAN],
            [[-2], 0.0, NAN],
            [[-2, 1], 0.0, NAN],
            [[-10, 5, -6, 5, 5], 0.0, NAN],
            [[-10, 5, -5, 5, 5, -1], 0.0, NAN],
            [[-10, 15, -7, 5, 6, -10], 0.0, NAN],
        ];
    }

    /**
     * @dataProvider dataProviderForProfitabilityIndex
     */
    public function testProfitabilityIndex(array $values, float $rate, float $pi)
    {
        $result = Finance::profitabilityIndex($values, $rate);
        $this->assertEquals($pi, $result, '', Finance::EPSILON);
    }

    /**
     * @return array
     */
    public function dataProviderForProfitabilityIndex(): array
    {
        return [
            [[-1], 0.1, 0.0],
            [[-1, 1], 0.0, 1.0],
            [[-1, 1, 1], 0.0, 2.0],
            [[-1, 1, 1, -1], 0.0, 1.0],
            [[-100, 50, 50, 50], 0.10, 1.2434259954921112],
            [[-50000, 65000], 0.0, 1.3],
            [[-50000, 65000], 0.01, 1.2871287128712872],
            [[-40000, 18000, 12000, 10000, 9000, 6000], 0.10, 1.0916697195298382],
            [[-40000, 18000, 12000, -10000, 9000, 6000], 0.10, 0.76091865698558803],
            [[-40000, 18000, 12000, -10000, 9000, 6000], 0.01, 0.88405904911326394],
            [[-40000, 18000, 12000, -10000, 9000, 6000], 0.0, 0.9],
        ];
    }

    /**
     * @dataProvider dataProviderForProfitabilityIndexNan
     */
    public function testProfitabilityIndexNan(array $values, float $rate)
    {
        $result = Finance::profitabilityIndex($values, $rate);
        $this->assertNan($result);
    }

    /**
     * @return array
     */
    public function dataProviderForProfitabilityIndexNan(): array
    {
        return [
            [[], 0.1, NAN],
            [[1], 0.1, NAN],
        ];
    }
}
