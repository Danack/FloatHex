<?php

declare(strict_types = 1);

use HexFloat\FloatInfo;
use HexFloat\Float32Info;

/**
 * Returns a string containing a hexadecimal representation of the given float,
 * using 64 bits of info
 *
 * @param float $number
 * @return string
 */
function floathex(float $number): string
{
    return strrev(unpack('h*', pack('d', $number))[1]);
}

/**
 * Returns a string containing a hexadecimal representation of the given float,
 * using 32 bits of info
 *
 * @param float $number
 * @return string
 */
function floathex32(float $num): string
{
    return strrev(unpack('h*', pack('f', $num))[1]);
}

/**
 * Convert a floating point number to a FloatInfo object,
 * which contains string representations of the float's sign,
 * exponent and mantissa
 * @param float $num
 * @return FloatInfo
 */
function float_info(float $num) : FloatInfo
{
    $float64 = floathex($num);

    //Sign bit: 1 bit
    //Exponent: 11 bits
    //Significand precision: 53 bits (52 explicitly stored)

    $chars = str_split($float64);


    // 3 bits from this
    $byte1 = hexdec($chars[0]);
    // 4 bits from this
    $byte2 = hexdec($chars[1]);

    // 1 bit from this
    $byte3 = hexdec($chars[2]);

    $sign = '0';

    if ($byte1 >= 8) {
        $sign = '1';
    }

    $exponentString = substr($float64, 0, 3);
    $exponentValue = hexdec($exponentString) & 0x7ff;
    $exponent = sprintf("%b", $exponentValue);
    $exponent = str_pad($exponent, 11, '0', STR_PAD_LEFT);

    $mantissa = substr($float64, 2);
    $mantissa = hexdec($mantissa) & 0xfffffffffffff;
    $mantissa = sprintf("%b", $mantissa);
    $mantissa = str_pad($mantissa, 52, '0', STR_PAD_LEFT);

    return new FloatInfo(
        $sign,
        $exponent,
        $mantissa,
    );
}

/**
 * Convert a floating point number to a Float32Info object,
 * which contains string representations of the float's sign,
 * exponent and mantissa
 *
 * @param float $num
 * @return Float32Info
 */
function float_info_32(float $num): Float32Info
{
    $float32 = floathex32($num);
    $chars = str_split($float32);

    // 3 bits from this
    $byte1 = hexdec($chars[0]);
    // 4 bits from this
    $byte2 = hexdec($chars[1]);

    // 1 bit from this
    $byte3 = hexdec($chars[2]);

    $sign = '0';

    if ($byte1 >= 8) {
        $sign = '1';
    }
    $exponent3Bits = ($byte1 & 0x7);
    $exponent4Bits = $byte2;
    $exponent1Bit = ($byte3 & 0x8) >> 3;
    $exponent = ($exponent3Bits << 5) | ($exponent4Bits << 1) | $exponent1Bit;

    $exponent = sprintf("%b", $exponent);
    $exponent = str_pad($exponent, 8, '0', STR_PAD_LEFT);

    $mantissa = substr($float32, 2, 6);
    $mantissa = hexdec($mantissa) & 0x7fffff;
    $mantissa = sprintf("%b", $mantissa);
    $mantissa = str_pad($mantissa, 23, '0', STR_PAD_LEFT);

    return new Float32Info(
        $sign,
        $exponent,
        $mantissa,
    );
}

/**
 * Produce a debug string that shows the Sign, Exponent and Mantissa for
 * two floating point numbers, using 64bit precision
 *
 *
 * @param float $value1
 * @param float $value2
 * @return string
 *
 * Example result
 * ┌──────┬─────────────┬──────────────────────────────────────────────────────┐
 * │ Sign │ Exponent    │ Mantissa                                             │
 * │    0 │ 01111111011 │ 1001100110011001100110011001100110011001100110011010 │
 * │    0 │ 10000011001 │ 0111110101111000010000000100000000000000000000000000 │
 * └──────┴─────────────┴──────────────────────────────────────────────────────┘
 *
 */
function float_compare(float $value1, float $value2): string
{
    $float_info_1 = float_info($value1);
    $float_info_2 = float_info($value2);

    //Sign bit: 1 bit
    //Exponent: 11 bits
    //Significand precision: 53 bits (52 explicitly stored)

    $output  = "┌──────┬─────────────┬──────────────────────────────────────────────────────┐\n";
    $output .= "│ Sign │ Exponent    │ Mantissa                                             │\n";

    $format_string = "│    %s │ %s │ %s │\n";

    $output .= sprintf($format_string, $float_info_1->getSign(), $float_info_1->getExponent(), $float_info_1->getMantissa());
    $output .= sprintf($format_string, $float_info_2->getSign(), $float_info_2->getExponent(), $float_info_2->getMantissa());

    $output .= "└──────┴─────────────┴──────────────────────────────────────────────────────┘\n";

    return $output;
}


/**
 * Produce a debug string that shows the Sign, Exponent and Mantissa for
 * two floating point numbers, using 32bit precision
 *
 * @param float $value1
 * @param float $value2
 * @return string
 *
 * Example result
 * ┌──────┬──────────┬─────────────────────────┐
 * │ Sign │ Exponent │ Mantissa                │
 * │    0 │ 01111011 │ 10011001100110011001101 │
 * │    0 │ 10011001 │ 01111101011110000100000 │
 * └──────┴──────────┴─────────────────────────┘
 *
 */
function float_compare_32(float $value1, float $value2): string
{
    $float_info_1 = float_info_32($value1);
    $float_info_2 = float_info_32($value2);

    $output  = "┌──────┬──────────┬─────────────────────────┐\n";
    $output .= "│ Sign │ Exponent │ Mantissa                │\n";

    $format_string = "│    %s │ %s │ %s │\n";

    $output .= sprintf($format_string, $float_info_1->getSign(), $float_info_1->getExponent(), $float_info_1->getMantissa());
    $output .= sprintf($format_string, $float_info_2->getSign(), $float_info_2->getExponent(), $float_info_2->getMantissa());

    $output .= "└──────┴──────────┴─────────────────────────┘\n";

    return $output;
}
