<?php

namespace App\MessageHandler;

class ProductionCalculator implements Calculator
{
    public function calculate(string $input): string
    {
        sleep(2 * 60);
        return 'real result';
    }
}
