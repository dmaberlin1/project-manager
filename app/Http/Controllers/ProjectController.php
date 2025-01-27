<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OpenWeatherMapService;
use App\Services\GitHubService;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(OpenWeatherMapService $weatherService, GitHubService $githubService)
    {
        $weather = $weatherService->getCurrentWeather('Kyiv');
        $repos = $githubService->getUserRepositories('your_github_username');

        // Получаем проекты, доступные текущему пользователю
        $projects = Project::when(Auth::user()->role !== 'admin', function ($query) {
            return $query->whereHas('tasks', function ($q) {
                $q->where('user_id', Auth::id());
            });
        })->get();
        return view('projects.index', compact('projects','weather','repos'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        if (Auth::user()->role === 'user') {
            abort(403, 'У вас нет прав для создания проекта.');
        }
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,archived'
        ]);
        $project = Project::create($request->all());

        return redirect()->route('projects.index')->with('success', 'Проект успешно создан.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Проверка доступа: пользователь видит только проекты с его задачами
        if (Auth::user()->role !== 'admin' && !$project->tasks->where('user_id', Auth::id())->count()) {
            abort(403, 'У вас нет прав для просмотра этого проекта.');
        }
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        if(Auth::user()->role ==='user'){
            abort(403,'У вас нет прав для редактирования проекта.');
        }
        return view('projects.edit',compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,archived',
        ]);
        $project->update($request->all());

        return redirect()->route('projects.index')->with('success','Проект успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'У вас нет прав для удаления проекта.');
        }
        $project->delete();
        return redirect()->route('projects.index')->with('success','Проект успешно удален.');
    }
}
