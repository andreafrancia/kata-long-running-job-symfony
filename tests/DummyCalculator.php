<?php

namespace App\Tests;

use App\MessageHandler\MakeLongCalculationHandler\Calculator;

class DummyCalculator implements Calculator
{
    private string $resultPrefix;

    public function __construct($resultPrefix)
    {
        $this->resultPrefix = $resultPrefix;
    }

    public function calculate(string $input): string
    {
        return "$this->resultPrefix $input";
    }
}
