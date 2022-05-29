<?php

namespace App\Tests;

use App\Entity\JobStatusAndResult;
use App\Entity\Job;
use App\Repository\JobRepository;
use App\Entity\JobStatus;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/** @group integration */
class JobRepositoryTest extends KernelTestCase
{
    private JobRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = self::$kernel->getContainer()->get('doctrine')->getRepository(Job::class);
        $this->repository->removeAllJobs();
    }

    public function testAddNewJob(): void
    {
        $this->repository->addNewJob('1ecdc6ce-1a82-605a-8724-f3236ab886a0');

        $jobs = $this->repository->findAllJobs();

        self::assertSame(['1ecdc6ce-1a82-605a-8724-f3236ab886a0'], $jobs);
    }

    /**
     * @throws Exception
     */
    public function testReadStatusOfJob(): void
    {
        $this->repository->addNewJob('1ecdc6ce-1a82-605a-8724-f3236ab886a0');

        $status = $this->repository->readJobStatusAndResult('1ecdc6ce-1a82-605a-8724-f3236ab886a0');

        self::assertEquals(
            new JobStatusAndResult(JobStatus::started, null),
            $status
        );
    }

    /**
     * @throws Exception
     */
    public function testTrackCompletion(): void
    {
        $this->repository->addNewJob('1ecdc6ce-1a82-605a-8724-f3236ab886a0');
        $this->repository->trackCompletion('1ecdc6ce-1a82-605a-8724-f3236ab886a0', "job result");

        $status = $this->repository->readJobStatusAndResult('1ecdc6ce-1a82-605a-8724-f3236ab886a0');

        self::assertEquals(
            new JobStatusAndResult(JobStatus::completed, "job result"),
            $status
        );
    }
}
