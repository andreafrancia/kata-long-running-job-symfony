<?php

namespace App\MessageHandler;

use App\Message\MakeLongCalculation;
use App\Repository\JobNotFoundException;
use App\Repository\JobRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class MakeLongCalculationHandler implements MessageHandlerInterface
{
    private Calculator $calculator;
    private JobRepository $repository;

    public function __construct(JobRepository $repository)
    {
        $this->calculator = new ProductionCalculator();
        $this->repository = $repository;
    }

    public function setCalculatorForTest(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @throws JobNotFoundException
     */
    public function __invoke(MakeLongCalculation $calculation)
    {
        $input = $this->repository->readInputDataForJob($calculation->jobId);
        $result = $this->calculator->calculate($input);
        $this->repository->trackCompletion($calculation->jobId, $result);
    }
}
