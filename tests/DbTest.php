<?php

namespace App\Tests;

use App\Entity\Job;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DbTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testCanConnectToDatabase(): void
    {
        /** @var JobRepository */
        $repository = self::$kernel->getContainer()->get('doctrine')->getRepository(Job::class);
        $repository->removeAllJobs();
        $repository->addJobWithId('1ecdc6ce-1a82-605a-8724-f3236ab886a0');

        $jobs = $repository->findAllIds();

        self::assertSame(['1ecdc6ce-1a82-605a-8724-f3236ab886a0'], $jobs);
    }
}
