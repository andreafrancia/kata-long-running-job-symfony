<?php

namespace App\Message;

class MakeLongCalculation
{
    public readonly string $jobId;

    public function __construct(string $jobId)
    {
        $this->jobId = $jobId;
    }
}
