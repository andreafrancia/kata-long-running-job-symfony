<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;

class RequestFromIp extends Request
{
    private string $ip;

    public function __construct(string $ip, array $query)
    {
        parent::__construct($query);
        $this->ip = $ip;
    }

    public function getClientIp(): ?string
    {
        return $this->ip;
    }
}