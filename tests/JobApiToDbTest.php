<?php

namespace App\Tests;

use App\Entity\JobStatusAndResult;
use App\Entity\Job;
use App\Entity\JobStatus;
use App\Repository\JobRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/** @group integration */
class JobApiToDbTest extends WebTestCase
{
    private JobRepository $repository;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        self::bootKernel();

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
        $this->client->request('GET', '/job/status/fcdad92e-dd57-4b14-ba00-32f7f991448b');

        // Asssert
        self::assertEquals('started', $this->jsonFromResponse()['status']);
        self::assertEquals(false, array_key_exists('result', $this->jsonFromResponse()));
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
        $this->client->request('GET', '/job/status/fcdad92e-dd57-4b14-ba00-32f7f991448b');

        // Assert
        self::assertEquals('completed', $this->jsonFromResponse()['status']);
        self::assertEquals('magical result', $this->jsonFromResponse()['result']);
    }

    private function addNewJob(string $jobId): array
    {
        $this->client->request('POST', "/job/add-new?job-id-for-test=$jobId");

        return $this->jsonFromResponse();
    }

    private function jsonFromResponse(): array
    {
        return json_decode($this->client->getResponse()->getContent(), true);
    }
}
