<?php

namespace App\Tests;

class ResourceFromString
{
    /**
     * @param string $input
     * @return false|resource
     * @noinspection PhpMissingReturnTypeInspection -> resource is not available as type
     */
    public static function resourceFromString(string $input)
    {
        return fopen('data://text/plain,' . $input, 'r');
    }
}
