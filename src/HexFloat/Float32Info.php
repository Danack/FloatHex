<?php

declare(strict_types = 1);

namespace HexFloat;

class Float32Info
{
    //Sign bit: 1 bit
    private string $sign;

    //Exponent: 11 bits
    private string $exponent;

    //Mantissa precision: 53 bits (52 explicitly stored)
    private string $mantissa;

    public function __construct(
        string $sign,
        string $exponent,
        string $mantissa
    ) {
        // TODO - check lengths
        $this->sign = $sign;
        $this->exponent = $exponent;
        $this->mantissa = $mantissa;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function getExponent(): string
    {
        return $this->exponent;
    }

    public function getMantissa(): string
    {
        return $this->mantissa;
    }
}
