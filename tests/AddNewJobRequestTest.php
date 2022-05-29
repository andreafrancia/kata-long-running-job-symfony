<?php

namespace App\Tests;

use App\UseCase\AddNewJob\AddNewJobRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

class AddNewJobRequestTest extends TestCase
{
    public function test()
    {
        $result = AddNewJobRequest::jobIdFrom(new Request());

        self::assertEquals(true, Uuid::isValid($result));
    }

    public function testIdForTest()
    {
        $result = AddNewJobRequest::jobIdFrom(
            new RequestFromIp(
                '::1',
                ['job-id-for-test' => 'eb3a34d2-7b47-4614-bf1d-59286708e476']
            )
        );

        self::assertEquals('eb3a34d2-7b47-4614-bf1d-59286708e476', $result);
    }

    public function testIdForTestNotAllowedFromExternalIps()
    {
        $result = AddNewJobRequest::jobIdFrom(
            new RequestFromIp(
                '10.0.0.1',
                ['job-id-for-test' => 'eb3a34d2-7b47-4614-bf1d-59286708e476']
            )
        );

        self::assertNotEquals('eb3a34d2-7b47-4614-bf1d-59286708e476', $result);
    }

}
