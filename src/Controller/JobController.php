<?php

namespace App\Controller;

use App\Message\MakeLongCalculation;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
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
    public function addNewJob(JobRepository $repository, MessageBusInterface $messageBus): JsonResponse
    {
        $jobId = $this->jobIdForTest ?? Uuid::v4();;

        $repository->addNewJob($jobId);
        $messageBus->dispatch(new MakeLongCalculation($jobId));

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
     * To test manually use (for example):
     *
     * curl https://localhost:8002/job/status/2552f7a5-d3a2-40cf-bbc1-9c8d51970608
     *
     */
    #[Route('/job/status/{id}', methods:['GET'])]
    public function readStatusofJob(JobRepository $repository, $id): JsonResponse
    {
        $jobStatusAndResult = $repository->readJobStatusAndResult($id);

        $result = [
            'status' => $jobStatusAndResult->status
        ];

        if($jobStatusAndResult->isCompleted())
            $result['result'] = $jobStatusAndResult->result;

        return $this->json($result);
    }
}
