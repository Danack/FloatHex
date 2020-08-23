<?php


///**
// * Used to convert to PHPUnits expected format.
// */
//function stringToRegexp(string $string): string
//{
//    $string = preg_quote($string, '#');
//
//    $replacements = [
//        '%s' => '.*',   // strings can be empty, so *
//        '%d' => '\d+',  // numbers can't be empty so +
//    ];
//
//    $string = str_replace(
//        array_keys($replacements),
//        array_values($replacements),
//        $string
//    );
//
//    return '#' . $string . '#iu';
//}
//

