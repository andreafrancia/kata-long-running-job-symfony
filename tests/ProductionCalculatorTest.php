<?php

namespace App\Tests;

use App\MessageHandler\ProductionCalculator;
use PHPUnit\Framework\TestCase;

class ProductionCalculatorTest extends TestCase
{
    private ProductionCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new ProductionCalculator();
    }

    public function testWhenDefinedByEnvironmentVariable(): void
    {
        $result = $this->calculator->getDuration(["JOB_DURATION" => "99"]);

        self::assertEquals(99, $result);
    }

    public function testDefaultVAlue(): void
    {
        $result = $this->calculator->getDuration([]);

        self::assertEquals(120, $result);
    }
}
