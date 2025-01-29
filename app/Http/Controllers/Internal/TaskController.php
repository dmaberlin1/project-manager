<?php

namespace App\Http\Controllers\Internal;

use App\Events\TaskCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskAuthorizationRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;

class TaskController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(TaskAuthorizationRequest $request)
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        return view('tasks.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());

        // Вызов события
        broadcast(new TaskCreated($task))->toOthers();

        return redirect()->route('tasks.index')->with('success', 'Задача успешно создана.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskAuthorizationRequest $request, Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskAuthorizationRequest $request, Task $task)
    {
        $projects = Project::all();
        return view('tasks.edit', compact('task', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->canUpdateProject($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Задача успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskAuthorizationRequest $request, Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Задача успешно удалена.');
    }
}
