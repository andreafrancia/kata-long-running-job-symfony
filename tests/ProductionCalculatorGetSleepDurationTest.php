<?php

namespace App\Tests;

use App\MessageHandler\MakeLongCalculationHandler\ProductionCalculator;
use PHPUnit\Framework\TestCase;

class ProductionCalculatorGetSleepDurationTest extends TestCase
{
    private ProductionCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new ProductionCalculator();
    }

    public function testConfiguredByEnvironemntVariable(): void
    {
        $result = $this->calculator->getSleepDuration(["JOB_DURATION" => "99"]);

        self::assertEquals(99, $result);
    }

    public function testDefaultValue(): void
    {
        $result = $this->calculator->getSleepDuration([]);

        self::assertEquals(120, $result);
    }
}
