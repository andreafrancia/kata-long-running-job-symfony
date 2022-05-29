<?php

namespace App\UseCase\AddNewJob;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

class AddNewJobRequest
{
    public readonly string $jobId;
    /** @var resource */
    public $input;

    /**
     * @param string $jobId
     * @param resource $input
     */
    public function __construct(string $jobId, $input)
    {
        $this->jobId = $jobId;
        $this->input = $input;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            self::jobIdFrom($request),
            self::inputFrom($request)
        );
    }

    public static function jobIdFrom(Request $request): string
    {
        if (in_array($request->getClientIp(), ['::1', '127.0.0.1'])) {
            $idForTest = $request->query->get('job-id-for-test');

            if ($idForTest) {
                return $idForTest;
            }
        }
        return Uuid::v4()->toRfc4122();
    }

    /**
     * @return resource
     */
    public static function inputFrom(Request $request)
    {
        return $request->getContent(true);
    }
}