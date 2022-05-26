<?php

namespace App\Tests;

use App\Entity\Job;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DbTest extends KernelTestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCanConnectToDatabase(): void
    {
        $this->entityManager->createQuery('DELETE FROM App\Entity\Job')->execute();
        /** @var JobRepository */
        $repository = self::$kernel->getContainer()->get('doctrine')->getRepository(Job::class);
        $job = new Job();
        $job->setId('1ecdc6ce-1a82-605a-8724-f3236ab886a0');
        $repository->add($job, true);

        $jobs = $repository->findAllIds();

        self::assertSame(['1ecdc6ce-1a82-605a-8724-f3236ab886a0'], $jobs);
    }
}
