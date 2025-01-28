<?php

namespace App\Exports;
use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
class TaskStatusExport implements FromQuery
{
    use Exportable;

    protected $projectId;
    public function __construct($projectId)
    {
        $this->projectId=$projectId;
    }

    public function query()
    {
        return Task::query()
            ->select('status',Task::query()->raw('COUNT(*) as count'))
            ->where('project_id',$this->projectId)
            ->groupBy('status');
    }
}
