<!-- resources/views/tasks/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Редактировать задачу')

@section('content')
    <h1>Редактировать задачу</h1>

    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Название задачи</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $task->name }}" required>
        </div>

        <div class="mb-3">
            <label for="project_id" class="form-label">Проект</label>
            <select name="project_id" id="project_id" class="form-select">
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="assignee_id" class="form-label">Исполнитель</label>
            <select name="assignee_id" id="assignee_id" class="form-select">
                <option value="">Не назначено</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $task->assignee_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <select name="status" id="status" class="form-select">
                <option value="to_do" {{ $task->status == 'to_do' ? 'selected' : '' }}>To Do</option>
                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Дедлайн</label>
            <input type="date" name="deadline" id="deadline" class="form-control" value="{{ $task->deadline ? $task->deadline->format('Y-m-d') : '' }}">
        </div>

        <button type="submit" class="btn btn-success">Сохранить изменения</button>
    </form>
@endsection
