<?php

namespace App\Services\Interfaces;

interface StatisticsInterface
{
    public function getTaskStatusCount(int $projectId);

    public function getAverageCompletionTime(int $projectId);

    public function getTopActiveUsers();
}
