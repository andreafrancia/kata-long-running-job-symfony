<?php

namespace App\Repository;

use App\Entity\JobStatusAndResult;
use App\Entity\Job;
use App\Entity\JobStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Job>
 *
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    /**
     * @param string $id
     * @param resource $input
     * @return void
     */
    public function addNewJob(string $id, $input): void
    {
        $job = new Job();
        $job->setId($id);
        $job->setStatus(JobStatus::started);
        $job->setInput($input);
        $this->getEntityManager()->persist($job);
        $this->getEntityManager()->flush();
    }

    public function removeAllJobs()
    {
        $this->getEntityManager()->createQuery('DELETE FROM App\Entity\Job')->execute();
    }

    /** @return string[] */
    public function findAllJobs(): array
    {
        return array_map(
            fn($job) => $job->getId(),
            $this->findAll()
        );
    }

    /**
     * @throws JobNotFoundException
     */
    public function readJobStatusAndResult(string $jobId): JobStatusAndResult
    {
        $job = $this->findOneJob($jobId);

        return new JobStatusAndResult(
            JobStatus::from($job->getStatus()),
            $job->getResult()
        );
    }

    public function trackCompletion(string $jobId, string $result)
    {
        /** @var Job $job */
        $job = $this->getEntityManager()->find(Job::class, $jobId);
        $job->setStatus(JobStatus::completed);
        $job->setResult($result);
        $this->getEntityManager()->persist($job);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws JobNotFoundException
     */
    public function readInputDataForJob(string $jobId): string
    {
        $job = $this->findOneJob($jobId);
        return $job->getInputAsString();
    }

    /**
     * @throws JobNotFoundException
     */
    private function findOneJob(string $jobId): Job
    {
        /** @var Job $job */
        $job = $this->find($jobId);

        if ($job === null) {
            throw new JobNotFoundException("Job not found, id: $jobId");
        }

        return $job;
    }
}
