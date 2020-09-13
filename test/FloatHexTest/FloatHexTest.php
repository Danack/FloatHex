<?php

declare(strict_types = 1);

namespace FloatHexTest;

use function FloatHex\floathex;
use function FloatHex\floathex32;
use function FloatHex\float_info;
use function FloatHex\float_info_32;
use function FloatHex\float_compare;
use function FloatHex\float_compare_32;
use function FloatHex\hexfloat;
use function FloatHex\hexfloat32;

/**
 * @coversNothing
 */
class FloatHexTest extends BaseTestCase
{
    public function provides64BitTests()
    {
        // taken from https://www.binaryconvert.com/result_double.html?decimal=049048048048048048048048049

        //     float        Hex expected,     sign, exponent,       Mantissa
        yield [0.0,         '0000000000000000', '0', '00000000000',  '0000000000000000000000000000000000000000000000000000'];
        yield [1.0,         '3ff0000000000000', '0', '01111111111',  '0000000000000000000000000000000000000000000000000000'];
        yield [2.0,         '4000000000000000', '0', '10000000000',  '0000000000000000000000000000000000000000000000000000'];

        yield [0.1,         '3fb999999999999a', '0', '01111111011',  '1001100110011001100110011001100110011001100110011010'];
        yield [0.25,        '3fd0000000000000', '0', '01111111101',  '0000000000000000000000000000000000000000000000000000'];
        yield [0.5,         '3fe0000000000000', '0', '01111111110',  '0000000000000000000000000000000000000000000000000000'];

        yield [-1.0,        'bff0000000000000', '1', '01111111111',  '0000000000000000000000000000000000000000000000000000'];

        yield [-11111.0,    'c0c5b38000000000', '1', '10000001100',  '0101101100111000000000000000000000000000000000000000'];
        yield [100000001.0, '4197d78404000000', '0', '10000011001',  '0111110101111000010000000100000000000000000000000000'];
    }

    /**
     * @dataProvider provides64BitTests
     * @covers ::FloatHex\floathex
     */
    public function test_floathex($input, $expectedHex, $signExpected, $exponentExpected, $mantissaExpected)
    {
        $result = floathex($input);

        $this->assertSame($result, $expectedHex);

        $floatInfo = float_info($input);

        $this->assertSame($signExpected, $floatInfo->getSign());
        $this->assertSame($exponentExpected, $floatInfo->getExponent());
        $this->assertSame($mantissaExpected, $floatInfo->getMantissa());
    }


    /**
     * @dataProvider provides64BitTests
     * @covers ::FloatHex\hexfloat
     */
    public function test_hexfloat($expectedNumber, $hexInput, $signExpected, $exponentExpected, $mantissaExpected)
    {
        $number = hexfloat($hexInput);

        $this->assertSame($expectedNumber, $number);
    }

    /**
     * @dataProvider provides64BitTests
     * @covers ::FloatHex\float_info
     */
    public function test_float_info($inputNumber, $hex, $signExpected, $exponentExpected, $mantissaExpected)
    {
        $floatInfo = float_info($inputNumber);

        $this->assertSame($signExpected, $floatInfo->getSign());
        $this->assertSame($exponentExpected, $floatInfo->getExponent());
        $this->assertSame($mantissaExpected, $floatInfo->getMantissa());
    }


    public function provides32BitTests()
    {
        // taken from https://www.binaryconvert.com/result_double.html?decimal=049048048048048048048048049

        //     float      Hex expected, sign, exponent,       Mantissa
        yield [0.0,       '00000000', '0', '00000000',  '00000000000000000000000'];
        yield [1.0,       '3f800000', '0', '01111111',  '00000000000000000000000'];
        yield [2.0,       '40000000', '0', '10000000',  '00000000000000000000000'];

        /*0.10000000149011612
        0.100000001490116119384765625*/

        yield [0.100000001490116119384765625,       '3dcccccd', '0', '01111011',  '10011001100110011001101' ];
        yield [0.25,        '3e800000', '0', '01111101',  '00000000000000000000000' ];
        yield [0.5,         '3f000000', '0', '01111110',  '00000000000000000000000'];

        yield [100000000.0, '4cbebc20', '0', '10011001',  '01111101011110000100000'];
        // 100000001.0 is not representable
        yield [100000008.0, '4cbebc21', '0', '10011001',  '01111101011110000100001'];
        yield [-1.0,        'bf800000', '1', '01111111',  '00000000000000000000000'];
    }


    /**
     * @dataProvider provides32BitTests
     * @covers ::FloatHex\floathex32
     */
    public function test_floathex32($input, $expectedHex, $signExpected, $exponentExpected, $mantissaExpected)
    {
        $result = floathex32($input);
        $this->assertSame($result, $expectedHex);
        $float32Info = float_info_32($input);

        $this->assertSame($signExpected, $float32Info->getSign());
        $this->assertSame($exponentExpected, $float32Info->getExponent());
        $this->assertSame($mantissaExpected, $float32Info->getMantissa());
    }


    /**
     * @dataProvider provides32BitTests
     * @covers ::FloatHex\hexfloat32
     */
    public function test_hexfloat32($expectedNumber, $inputHex, $signExpected, $exponentExpected, $mantissaExpected)
    {
        $result = hexfloat32($inputHex);
        $this->assertSame($expectedNumber, $result);
    }

    /**
     * @covers ::FloatHex\float_compare
     */
    public function test_float_compare()
    {
        $contents = float_compare(0.1, 100000001);

        $info1 = float_info(0.1);
        $this->assertStringContainsString($info1->getExponent(), $contents);
        $this->assertStringContainsString($info1->getMantissa(), $contents);

        $info2 = float_info(100000001);
        $this->assertStringContainsString($info2->getExponent(), $contents);
        $this->assertStringContainsString($info2->getMantissa(), $contents);
    }


    /**
     * @dataProvider provides32BitTests
     * @covers ::FloatHex\float_info_32
     */
    public function test_float_info_32($inputNumber, $hex, $signExpected, $exponentExpected, $mantissaExpected)
    {
        $floatInfo = float_info_32($inputNumber);

        $this->assertSame($signExpected, $floatInfo->getSign());
        $this->assertSame($exponentExpected, $floatInfo->getExponent());
        $this->assertSame($mantissaExpected, $floatInfo->getMantissa());
    }


    /**
     * @covers ::FloatHex\float_compare_32
     * @covers ::FloatHex\compare_strings
     */
    public function testCompare32()
    {
        $contents = float_compare_32(0.1, 100000001);

        $info1 = float_info_32(0.1);
        $this->assertStringContainsString($info1->getExponent(), $contents);
        $this->assertStringContainsString($info1->getMantissa(), $contents);

        $info2 = float_info_32(100000001);
        $this->assertStringContainsString($info2->getExponent(), $contents);
        $this->assertStringContainsString($info2->getMantissa(), $contents);
    }
}
