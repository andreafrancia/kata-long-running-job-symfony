<?php

namespace App\Controller;

use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class JobController extends AbstractController
{
    private ?string $jobIdForTest = null;

    /*
     * To test manually use:
     *
     * curl -X POST https://localhost:8002/job/add-new
     *
     */
    #[Route('/job/add-new', methods: ['POST'])]
    public function addNewJob(JobRepository $repository): JsonResponse
    {
        $jobId = $this->jobIdForTest ?? Uuid::v4();;

        $repository->addNewJob($jobId);

        return $this->json([
                               'message' => 'Job started',
                               'jobId' => $jobId,
                           ]);
    }

    public function setJobIdForTest(string $jobId)
    {
        $this->jobIdForTest = $jobId;
    }

    /*
     * To test manually use:
     *
     * curl https://localhost:8002/job/status
     *
     */
    #[Route('/job/status/{id}', methods:['GET'])]
    public function readStatusofJob(JobRepository $repository, $id): JsonResponse
    {
        $result = $repository->readJobStatus($id);

        return $this->json([
                               'status' => $result->status
                           ]);
    }
}
