<?php

namespace App\Tests;

use App\Controller\JobController;
use App\Entity\JobStatusAndResult;
use App\Entity\Job;
use App\Entity\JobStatus;
use App\Repository\JobRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/** @group integration */
class JobControllerTest extends WebTestCase
{
    private JobController $controller;
    private JobRepository $repository;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        self::bootKernel();

        /** @var JobController controller */
        $controller = self::$kernel->getContainer()->get('App\Controller\JobController');
        $this->controller = $controller;

        /** @var JobRepository $repository */
        $repository = self::$kernel->getContainer()->get('doctrine')->getRepository(Job::class);
        $this->repository = $repository;

        $this->repository->removeAllJobs();
    }

    /**
     * @throws Exception
     */
    public function testAddNewJob()
    {
        // Arrange

        // Act
        $response = $this->addNewJob("fcdad92e-dd57-4b14-ba00-32f7f991448b");

        // Assert
        $status = $this->repository->readJobStatusAndResult("fcdad92e-dd57-4b14-ba00-32f7f991448b");
        self::assertEquals(new JobStatusAndResult(JobStatus::started, null), $status);
        self::assertEquals('fcdad92e-dd57-4b14-ba00-32f7f991448b', $response['jobId']);
        self::assertEquals('Job started', $response['message']);
    }

    public function testReadJobStatusOfAStartedJob()
    {
        // Arrange
        $this->addNewJob("fcdad92e-dd57-4b14-ba00-32f7f991448b");

        // Act
        $result = $this->parseResult(
            $this->controller->readStatusOfJob(
                $this->repository,
                "fcdad92e-dd57-4b14-ba00-32f7f991448b"
            )
        );

        // Asssert
        self::assertEquals('started', $result['status']);
        self::assertEquals(false, array_key_exists('result', $result));
    }

    public function testReadJobStatusOfACompletedJob()
    {
        // Arrange
        $this->addNewJob("fcdad92e-dd57-4b14-ba00-32f7f991448b");
        $this->repository->trackCompletion(
            "fcdad92e-dd57-4b14-ba00-32f7f991448b",
            "magical result"
        );

        // Act
        $result = $this->parseResult(
            $this->controller->readStatusOfJob(
                $this->repository,
                "fcdad92e-dd57-4b14-ba00-32f7f991448b"
            )
        );

        // Assert
        self::assertEquals('completed', $result['status']);
        self::assertEquals('magical result', $result['result']);
    }

    private function parseResult(JsonResponse $response): array
    {
        return json_decode($response->getContent(), true);
    }

    private function addNewJob(string $jobId): array
    {
        $this->client->request('POST', "/job/add-new?job-id-for-test=$jobId");

        return json_decode($this->client->getResponse()->getContent(), true);
    }
}
