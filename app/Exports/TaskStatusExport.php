<?php

namespace App\Exports;

use App\Models\Task;
use Illuminate\Support\Facades\Response;

class TaskStatusExport
{
    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    public function exportCsv()
    {
        $tasks = Task::select('status', \DB::raw('COUNT(*) as count'))
            ->where('project_id', $this->projectId)
            ->groupBy('status')
            ->get();

        $csvHeader = ['Status', 'Count'];
        $csvData = $tasks->map(fn($task) => [$task->status, $task->count])->toArray();

        $csvOutput = fopen('php://temp', 'w');
        fputcsv($csvOutput, $csvHeader);

        foreach ($csvData as $row) {
            fputcsv($csvOutput, $row);
        }

        rewind($csvOutput);
        $csvContent = stream_get_contents($csvOutput);
        fclose($csvOutput);

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="task_statistics.csv"',
        ]);
    }

    public function exportJson()
    {
        $tasks = Task::select('status', \DB::raw('COUNT(*) as count'))
            ->where('project_id', $this->projectId)
            ->groupBy('status')
            ->get();

        return Response::json($tasks, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
