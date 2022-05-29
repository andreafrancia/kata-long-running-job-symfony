<?php

namespace App\Tests;

use App\MessageHandler\MakeLongCalculationHandler\ProductionCalculator;
use PHPUnit\Framework\TestCase;

class ProductionCalculatorTest extends TestCase
{
    private ProductionCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new ProductionCalculator();
    }

    public function test()
    {
        $_ENV['JOB_DURATION'] = 0;
        $result = $this->calculator->calculate("hello!");

        self::assertSame("HELLO!", $result);
    }
}
