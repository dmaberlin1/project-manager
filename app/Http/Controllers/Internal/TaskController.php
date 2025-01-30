<?php

namespace App\Http\Controllers\Internal;

use App\Events\TaskCreated;
use App\Exports\TaskStatusExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExportTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskAuthorizationRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(TaskAuthorizationRequest $request): View
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        $projects = Project::all();
        return view('tasks.create', compact('projects'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = Task::create($request->validated());

        broadcast(new TaskCreated($task))->toOthers();

        return redirect()->route('tasks.index')->with('success', 'Задача успешно создана.');
    }

    public function show(TaskAuthorizationRequest $request, Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(TaskAuthorizationRequest $request, Task $task): View
    {
        $projects = Project::all();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Задача успешно обновлена.');
    }

    public function destroy(TaskAuthorizationRequest $request, Task $task): RedirectResponse
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Задача успешно удалена.');
    }

    public function exportCsv(ExportTaskRequest $request, int $projectId)
    {
        return (new TaskStatusExport($projectId))->download('task_status.csv');
    }

    public function exportJson(ExportTaskRequest $request, int $projectId): JsonResponse
    {
        return (new TaskStatusExport($projectId))->exportJson();
    }
}
