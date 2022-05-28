<?php

namespace App\Tests;

use App\Controller\JobController;
use App\Enitty\JobStatusAndResult;
use App\Entity\Job;
use App\Entity\JobStatus;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class JobControllerTest extends KernelTestCase
{
    private JobController $controller;
    private JobRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var JobController controller */
        $controller = self::$kernel->getContainer()->get('App\Controller\JobController');
        $this->controller = $controller;

        /** @var JobRepository $repository */
        $repository = self::$kernel->getContainer()->get('doctrine')->getRepository(Job::class);
        $this->repository = $repository;
    }

    public function test()
    {
        $this->controller->setJobIdForTest("fcdad92e-dd57-4b14-ba00-32f7f991448b");

        $this->controller->addNewJob($this->repository);

        $status = $this->repository->readJobStatus("fcdad92e-dd57-4b14-ba00-32f7f991448b");
        self::assertEquals(new JobStatusAndResult(JobStatus::started, null), $status);
    }
}