<?php

namespace App\Entity;

enum JobStatus: string
{
    case started = 'started';
    case completed = 'completed';
}