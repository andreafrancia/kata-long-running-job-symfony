<?php

namespace App\MessageHandler\MakeLongCalculationHandler;

interface Calculator
{
    public function calculate(string $input): string;
}
