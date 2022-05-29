<?php

namespace App\Tests;

use App\Controller\JobController;
use App\Entity\JobStatusAndResult;
use App\Entity\Job;
use App\Entity\JobStatus;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/** @group integration */
class JobControllerTest extends KernelTestCase
{
    private JobController $controller;
    private JobRepository $repository;
    private MessageBusInterface $messageBus;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var JobController controller */
        $controller = self::$kernel->getContainer()->get('App\Controller\JobController');
        $this->controller = $controller;

        /** @var JobRepository $repository */
        $repository = self::$kernel->getContainer()->get('doctrine')->getRepository(Job::class);
        $this->repository = $repository;

        $this->messageBus = new class implements MessageBusInterface {

            public function dispatch(object $message, array $stamps = []): Envelope
            {
                return new Envelope(new \stdClass());
            }
        };

        $this->repository->removeAllJobs();
    }

    /**
     * @throws \Exception
     */
    public function testAddNewJob()
    {
        $this->controller->setJobIdForTest("fcdad92e-dd57-4b14-ba00-32f7f991448b");

        $result = $this->parseResult($this->invokeControllerAddNewJob());

        $status = $this->repository->readJobStatusAndResult("fcdad92e-dd57-4b14-ba00-32f7f991448b");
        self::assertEquals(new JobStatusAndResult(JobStatus::started, null), $status);
        self::assertEquals('fcdad92e-dd57-4b14-ba00-32f7f991448b', $result['jobId']);
        self::assertEquals('Job started', $result['message']);
    }

    public function testReadJobStatusOfAStartedJob()
    {
        $this->controller->setJobIdForTest("fcdad92e-dd57-4b14-ba00-32f7f991448b");
        $this->invokeControllerAddNewJob();

        $result = $this->parseResult(
            $this->controller->readStatusofJob($this->repository,
                                               "fcdad92e-dd57-4b14-ba00-32f7f991448b"));

        self::assertEquals('started', $result['status']);
        self::assertEquals(false, array_key_exists('result', $result));
    }

    public function testReadJobStatusOfACompletedJob()
    {
        $this->controller->setJobIdForTest("fcdad92e-dd57-4b14-ba00-32f7f991448b");
        $this->invokeControllerAddNewJob();
        $this->repository->trackCompletion("fcdad92e-dd57-4b14-ba00-32f7f991448b",
                                           "magical result");

        $result = $this->parseResult(
            $this->controller->readStatusofJob($this->repository,
                                               "fcdad92e-dd57-4b14-ba00-32f7f991448b"));

        self::assertEquals('completed', $result['status']);
        self::assertEquals('magical result', $result['result']);
    }

    private function parseResult(JsonResponse $response): array
    {
        return json_decode($response->getContent(), true);
    }

    private function invokeControllerAddNewJob(): JsonResponse
    {
        return $this->controller->addNewJob($this->repository, $this->messageBus);
    }

}