<?php

namespace App\Http\Controllers;

use App\Events\TaskCreated;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::when(Auth::user()->role === 'user', function ($query) {
            return $query->where('user_id', Auth::id());
        })->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects=Project::all();
        return view('tasks.create',compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:to_do,in_progress,done',
            'deadline' => 'nullable|date',
        ]);
        $task = Task::create($request->all());

        // Вызов события
        broadcast(new TaskCreated($task))->toOthers();

        return redirect()->route('tasks.index')->with('success','Задача успешно создана.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if (Auth::user()->role === 'user' && $task->user_id !== Auth::id()) {
            abort(403, 'У вас нет прав для просмотра этой задачи.');
        }
        return view('tasks.show',compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        if(Auth::user()->role==='user' && $task->user_id !==Auth::id()){
            abort(403,'У вас нет прав для редактирования этой задачи.');
        }
        $projects=Project::all();
        return view('tasks.edit',compact('task','projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:to_do,in_progress,done',
            'deadline' => 'nullable|date',
        ]);
        $task->update($request->all());
        return redirect()->route('tasks.index')->with('success','Задача успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if(Auth::user()->role==='user' && $task->user_id !== Auth::id()){
            abort(403,'У вас нет прав для удаления этой задачи.');
        }
        $task->delete();
        return redirect()->route('tasks.index')->with('success','Задача успешно удалена.');
    }
}
