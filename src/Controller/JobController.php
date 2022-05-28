<?php

namespace App\Controller;

use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    private ?string $jobIdForTest = null;

    #[Route('/job/add-new', name: 'app_job', methods: ['POST'])]
    public function addNewJob(JobRepository $repository): JsonResponse
    {
        $repository->addNewJob($this->jobIdForTest);
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/JobController.php',
        ]);
    }

    public function setJobIdForTest(string $jobId)
    {
        $this->jobIdForTest = $jobId;
    }
}
