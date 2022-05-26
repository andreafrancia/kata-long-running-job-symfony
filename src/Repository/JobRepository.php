<?php

namespace App\Repository;

use App\Entity\Job;
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

    public function addJobWithId(string $id)
    {
        $job = new Job();
        $job->setId($id);
        $this->getEntityManager()->persist($job);
        $this->getEntityManager()->flush();
    }

    public function removeAllJobs()
    {
        $this->getEntityManager()->createQuery('DELETE FROM App\Entity\Job')->execute();
    }

    /** @return string[] */
    public function findAllIds(): array
    {
        return array_map(function($job) {
            return $job->getId();
        }, $this->findAll());
    }

}
