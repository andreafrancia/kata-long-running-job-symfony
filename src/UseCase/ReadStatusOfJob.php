<?php

namespace App\UseCase;

use App\Repository\JobNotFoundException;
use App\Repository\JobRepository;
use App\UseCase\ReadStatusOfJob\ReadStatusOfJobReply;

class ReadStatusOfJob
{
    private readonly JobRepository $repository;

    public function __construct(JobRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws JobNotFoundException
     */
    public function invoke(string $id): ReadStatusOfJobReply
    {
        $jobStatusAndResult = $this->repository->readJobStatusAndResult($id);

        return new ReadStatusOfJobReply(
            $jobStatusAndResult->status,
            $jobStatusAndResult->isCompleted(),
            $jobStatusAndResult->result,
        );
    }
}
