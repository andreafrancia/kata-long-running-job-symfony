<?php

namespace App\Controller;

use App\Repository\JobNotFoundException;
use App\Repository\JobRepository;
use App\UseCase\AddNewJob;
use App\UseCase\ReadStatusOfJob;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class JobController extends AbstractController
{
    /*
     * To test manually use:
     *
     * curl -X POST \
     *         https://localhost:8002/job/add-new\?job-id-for-test\=1030572f-069e-4fec-bc94-77f9a52ce43e  \
     *         --data "input"
     *
     */
    #[Route('/job/add-new', methods: ['POST'])]
    public function addNewJob(
        JobRepository $repository,
        MessageBusInterface $messageBus,
        Request $request
    ): JsonResponse {
        $useCase = new AddNewJob($repository, $messageBus);

        $jobId = $this->jobIdFor($request);
        $reply = $useCase->invoke($jobId);

        return $this->json([
                               'message' => $reply->message,
                               'jobId' => $reply->jobId,
                           ]);
    }

    private function jobIdFor(Request $request): string
    {
        if (in_array($request->getClientIp(), ['::1', '127.0.0.1'])) {
            return $request->query->get('job-id-for-test');
        }
        return Uuid::v4()->toRfc4122();
    }

    /*
     * To test manually use (for example):
     *
     * curl https://localhost:8002/job/status/2552f7a5-d3a2-40cf-bbc1-9c8d51970608
     *
     */
    #[Route('/job/status/{id}', methods:['GET'])]
    public function readStatusOfJob(JobRepository $repository, $id): JsonResponse
    {
        try {
            $useCase = new ReadStatusOfJob($repository);

            $reply = $useCase->invoke($id);

            return $this->json($reply->toDataForJsonResponse());
        } catch (JobNotFoundException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }
}
