<?php
namespace App\Tests;

use App\Entity\Job;
use App\Message\MakeLongCalculation;
use App\MessageHandler\Calculator;
use App\MessageHandler\MakeLongCalculationHandler;
use App\Repository\JobRepository;
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

        $this->handler->setCalculatorForTest(new DummyCalculator("dummy-result"));
    }

    function test()
    {
        $this->repository->addNewJob("482ab5fc-be7e-417e-b4f0-a351ca762036");

        $this->handler->__invoke(new MakeLongCalculation("482ab5fc-be7e-417e-b4f0-a351ca762036"));

        $jobStatus = $this->repository->readJobStatusAndResult("482ab5fc-be7e-417e-b4f0-a351ca762036");
        self::assertEquals(true, $jobStatus->isCompleted());
    }
}

class DummyCalculator implements Calculator
{
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function calculate(): string
    {
        return $this->result;
    }
}