<?php

namespace App\MessageHandler;

class ProductionCalculator implements Calculator
{
    public function calculate(string $input): string
    {
        sleep($this->getDuration($_ENV));
        return strtoupper($input);
    }

    public function getDuration(array $env): int
    {
        return intval($env['JOB_DURATION'] ?? 2 * 60);
    }
}
