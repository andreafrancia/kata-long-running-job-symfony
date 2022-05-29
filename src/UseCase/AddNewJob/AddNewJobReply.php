<?php

namespace App\UseCase\AddNewJob;

class AddNewJobReply
{
    public readonly string $message;
    public readonly string $jobId;

    public function __construct(string $message, string $jobId)
    {
        $this->message = $message;
        $this->jobId = $jobId;
    }
}
