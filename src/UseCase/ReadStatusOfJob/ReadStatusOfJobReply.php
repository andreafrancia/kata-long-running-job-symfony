<?php

namespace App\UseCase\ReadStatusOfJob;

use App\Entity\JobStatus;
use JetBrains\PhpStorm\ArrayShape;

class ReadStatusOfJobReply
{
    public readonly JobStatus $status;
    public readonly bool $isCompleted;
    public readonly ?string $result;

    public function __construct(JobStatus $status, bool $isCompleted, ?string $result)
    {
        $this->status = $status;
        $this->isCompleted = $isCompleted;
        $this->result = $result;
    }


    #[ArrayShape(['status' => "\App\Entity\JobStatus", 'result' => "\App\Entity\JobStatus|null|string"])]
    public function toDataForJsonResponse(): array
    {
        $jsonReply = [
            'status' => $this->status
        ];

        if ($this->isCompleted) {
            $jsonReply['result'] = $this->result;
        }

        return $jsonReply;
    }
}
