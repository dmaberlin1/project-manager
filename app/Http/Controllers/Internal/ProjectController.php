<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectAuthorizationRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Jobs\DeleteProjectJob;
use App\Models\Project;

class ProjectController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ProjectAuthorizationRequest $request)
    {
        $projects = Project::where('user_id', auth()->id())->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(ProjectAuthorizationRequest $request)
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        Project::create($request->validated());
        return redirect()->route('projects.index')->with('success', 'Проект успешно создан.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectAuthorizationRequest $request, Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectAuthorizationRequest $request, Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, ProjectAuthorizationRequest $authRequest, Project $project)
    {
        $project->canUpdateProject($request->all());
        return redirect()->route('projects.index')->with('success', 'Проект успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectAuthorizationRequest $request, Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Проект успешно удален.');
    }

    public function deleteProjectDelayed($id)
    {
        $delay = now()->addMinutes(10);
        // Поставить задачу на удаление проекта через 10 мин
        DeleteProjectJob::dispatch($id)->delay($delay);
        return response()->json(['message' => 'Проект будет удален через 10 минут']);
    }
}
