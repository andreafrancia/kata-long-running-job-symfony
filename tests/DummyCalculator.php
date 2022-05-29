<?php

namespace App\Tests;

use App\MessageHandler\Calculator;

class DummyCalculator implements Calculator
{
    private string $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function calculate(): string
    {
        return $this->result;
    }
}
