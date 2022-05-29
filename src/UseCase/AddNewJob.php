<?php

namespace App\UseCase;

use App\Message\MakeLongCalculation;
use App\Repository\JobRepository;
use App\UseCase\AddNewJob\AddNewJobReply;
use Symfony\Component\Messenger\MessageBusInterface;

class AddNewJob
{
    private readonly JobRepository $repository;
    private readonly MessageBusInterface $messageBus;

    public function __construct(JobRepository $repository, MessageBusInterface $messageBus)
    {
        $this->repository = $repository;
        $this->messageBus = $messageBus;
    }

    public function invoke(string $jobId): AddNewJobReply
    {
        $this->repository->addNewJob($jobId);
        $this->messageBus->dispatch(new MakeLongCalculation($jobId));

        return new AddNewJobReply('Job started', $jobId);
    }
}
