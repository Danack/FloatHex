# FloatHex

Functions for converting float to a hex-string and back again, and also for showing two floating point numbers as a binary representation.

[![Build Status](https://travis-ci.org/Danack/FloatHex.svg?branch=master)](https://travis-ci.org/Danack/FloatHex)

## Example

```php
<?php
require __DIR__ . "/vendor/autoload.php";

use function FloatHex\floathex;
use function FloatHex\floathex32;
use function FloatHex\float_compare;

$value = 1.2345;

echo floathex($value) . "\n";
// Output: 3ff3c083126e978d

echo floathex32($value) . "\n";
// Output: 3f9e0419

echo float_compare(1.2345, 1.234500000001);
// Output:
// ┌──────┬─────────────┬──────────────────────────────────────────────────────┐
// │ Sign │ Exponent    │ Mantissa                                             │
// │    0 │ 01111111111 │ 0011110000001000001100010010011011101001011110001101 │
// │    0 │ 01111111111 │ 0011110000001000001100010010011011101010100100100101 │
// │    - │ ----------- │ --------------------------------------xxxxx-x-x-x--- │
// └──────┴─────────────┴──────────────────────────────────────────────────────┘

```

## Why?

Some of the tests in Imagick compare floats, and trying to figure out if the float is off by a rounding error, or by a larger amount was annoying.

Comparing the bits makes it easier to see what is going on visually. e.g. for comparing 0.3 with 0.1 + 0.2: 

```php
<?php

echo float_compare(0.3, 0.1 + 0.2);

```

The output is:
```
┌──────┬─────────────┬──────────────────────────────────────────────────────┐
│ Sign │ Exponent    │ Mantissa                                             │
│    0 │ 01111111101 │ 0011001100110011001100110011001100110011001100110011 │
│    0 │ 01111111101 │ 0011001100110011001100110011001100110011001100110100 │
│    - │ ----------- │ -------------------------------------------------xxx │
└──────┴─────────────┴──────────────────────────────────────────────────────┘
```
Straight away, you can see that the difference between the numbers is in the lowest bit.

hexfloat and floathex32 might be useful for people who need to transfer floats precisely over text channels.  


## Exact float value

If you want to see the exact value that a float is stored as, this can be done with `number_format`.    

```
echo number_format(1.22,  64);

// Output is:
// 1.2199999999999999733546474089962430298328399658203125000000000000
```

## TODO 

* Check behaviour around NANs and INFs
* Error detection on bad input strings.