<?php

namespace App\Http\Controllers\Api;

use App\Exports\TaskStatusExport;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class StatisticsController extends Controller
{
    public function taskStatusCount($projectId)
    {
        $data = Cache::remember("task_status_{$projectId}", 3600, static function () use ($projectId) {
            Task::query()->selectRaw('status,COUNT(*) as count')
                ->where('project_id', $projectId)
                ->groupBy('status')
                ->get();
        });
        return response()->json($data);
    }

    public function averageCompletionTime($projectId)
    {
        $data = Cache::remember("average_completion_time_${projectId}", 3600, static function () use ($projectId) {
            return Task::query()->where('project_id', $projectId)
                ->where('status', 'Done')
                ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as avg_time')
                ->first();
        });
        return response()->json(['average_time' => $data->avg_time]);
    }

    public function topActiveUsers()
    {
        $users = Cache::remember('top_active_users', 3600, function () {
            return User::query()->withCount('tasks')
                ->orderBy('tasks_count', 'desc')
                ->take(3)
                ->get();
        });
        return response()->json($users);
    }

    public function exportTaskStatusToCsv($projectId)
    {
        return (new TaskStatusExport($projectId))->download('task_status.csv');
    }
}
