<?php

namespace App\Http\Controllers\Api;

use App\Exports\TaskStatusExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExportTaskRequest;
use App\Services\Interfaces\StatisticsInterface;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    protected StatisticsInterface $statisticsService;

    public function __construct(StatisticsInterface $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function taskStatusCount(int $projectId): JsonResponse
    {
        $data = $this->statisticsService->getTaskStatusCount($projectId);
        return response()->json($data);
    }

    public function averageCompletionTime(int $projectId): JsonResponse
    {
        $data = $this->statisticsService->getAverageCompletionTime($projectId);
        return response()->json(['average_time' => $data]);
    }

    public function topActiveUsers(): JsonResponse
    {
        $users = $this->statisticsService->getTopActiveUsers();
        return response()->json($users);
    }

    public function exportCsv(ExportTaskRequest $request, int $projectId): \Illuminate\Http\Response
    {
        return (new TaskStatusExport($projectId))->exportCsv();
    }

    public function exportJson(ExportTaskRequest $request, int $projectId): JsonResponse
    {
        return (new TaskStatusExport($projectId))->exportJson();
    }
}
