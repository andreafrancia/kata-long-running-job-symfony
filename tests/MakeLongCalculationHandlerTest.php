<?php

namespace App\Tests;

use App\Entity\Job;
use App\Message\MakeLongCalculation;
use App\MessageHandler\MakeLongCalculationHandler;
use App\Repository\JobRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/** @group integration */
class MakeLongCalculationHandlerTest extends KernelTestCase
{
    private MakeLongCalculationHandler $handler;
    private JobRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        /** @var JobRepository $repository */
        $repository = self::$kernel->getContainer()->get('doctrine')->getRepository(Job::class);
        $this->repository = $repository;

        $this->handler = new MakeLongCalculationHandler($repository);

        $this->handler->setCalculatorForTest(new DummyCalculator("dummy-result for"));

        $repository->removeAllJobs();
    }

    /**
     * @throws Exception
     */
    public function test()
    {
        $this->repository->addNewJob(
            "482ab5fc-be7e-417e-b4f0-a351ca762036",
            ResourceFromString::resourceFromString('data')
        );

        $this->handler->__invoke(new MakeLongCalculation("482ab5fc-be7e-417e-b4f0-a351ca762036"));

        $jobStatus = $this->repository->readJobStatusAndResult("482ab5fc-be7e-417e-b4f0-a351ca762036");
        self::assertEquals(true, $jobStatus->isCompleted());
        self::assertEquals('dummy-result for data', $jobStatus->result);
    }
}
