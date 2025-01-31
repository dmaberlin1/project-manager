<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Services\Interfaces\StatisticsInterface;
use Illuminate\Support\Facades\Cache;

class StatisticsService implements StatisticsInterface
{

    public function getTaskStatusCount(int $projectId)
    {
        return Cache::remember("task_status_{$projectId}", 3600, static function () use ($projectId) {
            return Task::query()
                ->selectRaw('status, COUNT(*) as count')
                ->where('project_id', $projectId)
                ->groupBy('status')
                ->get();
        });
    }


    public function getAverageCompletionTime(int $projectId)
    {
        $data = Cache::remember("average_completion_time_{$projectId}", 3600, static function () use ($projectId) {
            return Task::query()
                ->where('project_id', $projectId)
                ->where('status', 'Done')
                ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as avg_time')
                ->first();
        });

        return $data ? $data->avg_time : null;
    }

    public function getTopActiveUsers()
    {
        return Cache::remember('top_active_users', 3600, static function () {
            return User::query()
                ->withCount('tasks')
                ->orderBy('tasks_count', 'desc')
                ->take(3)
                ->get();
        });
    }
}
