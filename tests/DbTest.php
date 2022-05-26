<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DbTest extends KernelTestCase
{
    public function testCanConnectToDatabase(): void
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $query = $entityManager->getConnection()->executeQuery('SELECT 1');

        $result = $query->fetchOne();

        self::assertSame(1, $result);
    }
}
