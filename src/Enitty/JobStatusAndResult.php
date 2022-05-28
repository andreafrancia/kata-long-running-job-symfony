<?php

namespace App\Enitty;

use App\Entity\JobStatus;

class JobStatusAndResult
{
    public readonly JobStatus $status;
    public readonly ?string $result;

    public function __construct(JobStatus $status, ?string $result)
    {
        $this->status = $status;
        $this->result = $result;
    }

    public function isCompleted(): bool
    {
        return $this->status === JobStatus::completed;
    }
}