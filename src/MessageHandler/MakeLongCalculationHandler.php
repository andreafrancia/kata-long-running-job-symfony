<?php
namespace App\MessageHandler;

use App\Message\MakeLongCalculation;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class MakeLongCalculationHandler implements MessageHandlerInterface
{
    public function __invoke(MakeLongCalculation $calculation)
    {
        sleep(10);
        dump($calculation);
    }
}
