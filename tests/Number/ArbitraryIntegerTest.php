<?php

namespace MathPHP\Tests\Number;

use MathPHP\Number\ArbitraryInteger;
use MathPHP\Exception;

class ArbitraryIntegerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForStringToString
     */
    public function testStringToString(string $int, string $expected)
    {
        $obj = new ArbitraryInteger($int);

        $this->assertSame($expected, $obj->__toString());
        $this->assertSame($expected, (string) $obj);
    }

    public function dataProviderForStringToString()
    {
        return [
            ['200', '200'],
            ['123456789012345678901234567890', '123456789012345678901234567890'],
            ['0b1101', '13'],
            ['0xff', '255'],
            ['0127', '87'],
            ['-31415', '-31415'],
        ];
    }

    /**
     * @dataProvider dataProviderForIntToInt
     */
    public function testIntToInt(int $int)
    {
        $obj = new ArbitraryInteger($int);

        $this->assertSame($int, $obj->toInteger());
    }

    public function dataProviderForIntToInt()
    {
        return [
            [200],
            [123456],
            [PHP_INT_MAX],
            [31415],
            [-31415],
        ];
    }

    /**
     * @dataProvider dataProviderForStringToFloat
     */
    public function testIntToFloat(string $int, float $float)
    {
        $obj = new ArbitraryInteger($int);

        $this->assertSame($float, $obj->toFloat());
    }

    public function dataProviderForStringToFloat()
    {
        return [
            ['200', 200.0],
            ['123456', 123456.0],
            ['9223372036854775808', 9.223372036854775808E18],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForTestAddition
     */
    public function testAddition(string $int1, string $int2, string $expected)
    {
        $int1 = new ArbitraryInteger($int1);
        $int2 = new ArbitraryInteger($int2);
        $this->assertEquals($expected, (string) $int1->add($int2));
    }

    public function dataProviderForTestAddition()
    {
        return [
            ['0', '1', '1'],
            ['0', '-1', '-1'],
            ['-1', '0', '-1'],
            ['-1', '-2', '-3'],
            ['-2', '-1', '-3'],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForTestSubtract
     */
    public function testSubtract(string $int1, string $int2, string $expected)
    {
        $int1 = new ArbitraryInteger($int1);
        $int2 = new ArbitraryInteger($int2);
        $this->assertEquals($expected, (string) $int1->subtract($int2));
    }

    public function dataProviderForTestSubtract()
    {
        return [
            ['0', '1', '-1'],
            ['0', '-1', '1'],
            ['-1', '-2', '1'],
            ['-2', '-1', '-1'],
        ];
    }

    /**
     * @test         Intdiv calculates the correct whole and remainder
     * @dataProvider dataProviderForIntDivSmallDivisor
     * @param        string $dividend
     * @param        int $divisor
     * @param        string $int
     * @param        string $mod
     */
    public function testIntDivSmallDivisor(string $dividend, int $divisor, string $int, string $mod)
    {
        $obj = new ArbitraryInteger($dividend);
        $result_int =  $obj->intdiv($divisor);
        $result_mod =  $obj->mod($divisor);
        $this->assertEquals($int, (string) $result_int);
        $this->assertEquals($mod, (string) $result_mod);
    }

    public function dataProviderForIntDivSmallDivisor()
    {
        return [
            ['2134567896543378631213', 2, '1067283948271689315606', '1'],
            ['2134567896543378631213', 100, '21345678965433786312', '13'],
            ['301', 300, '1', '1'],
        ];
    }

    /**
     * @test         Intdiv calculates the correct whole and remainder
     * @dataProvider dataProviderForIntDivLargeDivisor
     * @param        string $dividend
     * @param        int $divisor
     * @param        string $int
     * @param        string $mod
     */
    public function testIntDivLargeDivisor(string $dividend, string $divisor, string $int, string $mod)
    {
        $obj = new ArbitraryInteger($dividend);
        $divisor = new ArbitraryInteger($divisor);
        $result_int =  $obj->intdiv($divisor);
        $result_mod =  $obj->mod($divisor);
        $this->assertEquals($int, (string) $result_int);
        $this->assertEquals($mod, (string) $result_mod);
    }
    public function dataProviderForIntDivLargeDivisor()
    {
        return [
            ['2134567896543378631213', '1067283948271689315606', '2', '1'],
            ['2134567896543378631213', '21345678965433786312', '100', '13'],
        ];
    }

    /**
     * @test         pow() returns the proper result
     * @dataProvider dataProviderForPow
     * @param        number $int
     * @param        string $expected
     */
    public function testPow($int, $exponent, $expected)
    {
        $int =  new ArbitraryInteger($int);
        $pow = $int->pow($exponent);
        $this->assertSame($expected, (string) $pow);
    }

    public function dataProviderForPow()
    {
        return [
            [
                1000000000,
                6,
                '1000000000000000000000000000000000000000000000000000000',
            ],
        ];
    }

    /**
     * @test         abs() returns the proper result
     * @dataProvider dataProviderForAbs
     * @param        string $int
     * @param        string $expected
     */
    public function testAbs(string $int, string $expected)
    {
        $abs = new ArbitraryInteger($int);
        $this->assertEquals($expected, (string) $abs->abs());
    }

    public function dataProviderForAbs()
    {
        return [
            ['-12345678910', '12345678910'],
        ];
    }

    /**
     * @test         negate() returns the proper result
     * @dataProvider dataProviderForNegate
     * @param        string $int
     * @param        string $expected
     */
    public function testNegate(string $int, string $expected)
    {
        $neg = new ArbitraryInteger($int);
        $this->assertEquals($expected, (string) $neg->negate());
    }

    public function dataProviderForNegate()
    {
        return [
            ['-123456789101112', '123456789101112'],
            ['123456789101112', '-123456789101112'],
            ['0', '0'],
        ];
    }

    /**
     * @test         fact() returns the proper result
     * @dataProvider dataProviderForFact
     * @param        number $int
     * @param        string $expected
     */
    public function testFact($int, $expected)
    {
        $fact = ArbitraryInteger::fact($int);
        $expected_obj = new ArbitraryInteger($expected);
        $this->assertEquals($expected_obj->getBinary(), $fact->getBinary());
        $this->assertTrue($expected_obj->equals($fact));
        $this->assertSame($expected, (string) $fact);
    }

    public function dataProviderForFact()
    {
        return [
            [
                1000,
                '402387260077093773543702433923003985719374864210714632543799910429938512398629020592044208486969404800479988610197196058631666872994808558901323829669944590997424504087073759918823627727188732519779505950995276120874975462497043601418278094646496291056393887437886487337119181045825783647849977012476632889835955735432513185323958463075557409114262417474349347553428646576611667797396668820291207379143853719588249808126867838374559731746136085379534524221586593201928090878297308431392844403281231558611036976801357304216168747609675871348312025478589320767169132448426236131412508780208000261683151027341827977704784635868170164365024153691398281264810213092761244896359928705114964975419909342221566832572080821333186116811553615836546984046708975602900950537616475847728421889679646244945160765353408198901385442487984959953319101723355556602139450399736280750137837615307127761926849034352625200015888535147331611702103968175921510907788019393178114194545257223865541461062892187960223838971476088506276862967146674697562911234082439208160153780889893964518263243671616762179168909779911903754031274622289988005195444414282012187361745992642956581746628302955570299024324153181617210465832036786906117260158783520751516284225540265170483304226143974286933061690897968482590125458327168226458066526769958652682272807075781391858178889652208164348344825993266043367660176999612831860788386150279465955131156552036093988180612138558600301435694527224206344631797460594682573103790084024432438465657245014402821885252470935190620929023136493273497565513958720559654228749774011413346962715422845862377387538230483865688976461927383814900140767310446640259899490222221765904339901886018566526485061799702356193897017860040811889729918311021171229845901641921068884387121855646124960798722908519296819372388642614839657382291123125024186649353143970137428531926649875337218940694281434118520158014123344828015051399694290153483077644569099073152433278288269864602789864321139083506217095002597389863554277196742822248757586765752344220207573630569498825087968928162753848863396909959826280956121450994871701244516461260379029309120889086942028510640182154399457156805941872748998094254742173582401063677404595741785160829230135358081840096996372524230560855903700624271243416909004153690105933983835777939410970027753472000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'
            ],
        ];
    }

    /**
     * @test         isqrt calculates the correct value
     * @dataProvider dataProviderForIsqrt
     * @param        string $number
     * @param        string $expected
     */
    public function testIsqrt(string $number, string $expected)
    {
        $obj = new ArbitraryInteger($number);
        $this->assertEquals($expected, (string) $obj->isqrt());
    }

    public function dataProviderForIsqrt()
    {
        return [
            ['8', '2'],
            ['9', '3'],
            ['10', '3'],
            ['110', '10'],
            ['64000', '252'],
            ['33600000', '5796'],
            ['123456789101112', '11111111'],
            ['152399025', '12345'],
            ['152399026', '12345'],
            ['152399024', '12344'],
        ];
    }

    /**
     * @test Test that greaterThan () works as it should
     * @dataProvider dataProviderForTestGreaterThan
     */
    public function testGreaterThan(string $int1, string $int2, bool $expected)
    {
        $int1 = new ArbitraryInteger($int1);
        $int2 = new ArbitraryInteger($int2);
        $this->assertEquals($expected, $int1->greaterThan($int2));
    }

    public function dataProviderForTestGreaterThan()
    {
        return [
            ['0', '-1', true],
            ['-1', '0', false],
            ['0', '0', false],
        ];
    }

    /**
     * @test     Constructor throws an exception when given an empty string
     * @throws   \Exception
     */
    public function testEmptyStringException()
    {
        // Given
        $number = "";

        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        $int =  new ArbitraryInteger($number);
    }

    /**
     * @test         Constructor throws an exception when given a float
     * @dataProvider dataproviderForTestIncorrectTypeException
     * @throws       \Exception
     */
    public function testIncorrectTypeException($number)
    {
        // Given
        // $number

        // Then
        $this->expectException(Exception\IncorrectTypeException::class);

        // When
        $int =  new ArbitraryInteger($number);
    }

    public function dataproviderForTestIncorrectTypeException()
    {
        return [
            // float
            [3.14],
            // array
            [
                ['123', '456']
            ],
            // bool
            [true],
            // object
            [new \stdClass()],
        ];
    }
}
