<?php

namespace App\Http\Controllers\Api;

use App\Exports\TaskStatusExport;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\StatisticsInterface;

class StatisticsController extends Controller
{
    protected StatisticsInterface $statisticsService;

    public function __construct(StatisticsInterface $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }


    public function taskStatusCount(int $projectId)
    {
        $data = $this->statisticsService->getTaskStatusCount($projectId);
        return response()->json($data);
    }


    public function averageCompletionTime(int $projectId)
    {
        $data = $this->statisticsService->getAverageCompletionTime($projectId);
        return response()->json(['average_time' => $data]);
    }

    public function topActiveUsers()
    {
        $users = $this->statisticsService->getTopActiveUsers();
        return response()->json($users);
    }


    public function exportTaskStatusToCsv(int $projectId)
    {
        return (new TaskStatusExport($projectId))->download('task_status.csv');
    }
}
