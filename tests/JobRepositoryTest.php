<?php

namespace App\Tests;

use App\Entity\Job;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class JobRepositoryTest extends KernelTestCase
{
    private JobRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = self::$kernel->getContainer()->get('doctrine')->getRepository(Job::class);
        $this->repository->removeAllJobs();
    }

    public function testCanConnectToDatabase(): void
    {
        $this->repository->addJobWithId('1ecdc6ce-1a82-605a-8724-f3236ab886a0');

        $jobs = $this->repository->findAllIds();

        self::assertSame(['1ecdc6ce-1a82-605a-8724-f3236ab886a0'], $jobs);
    }
}
