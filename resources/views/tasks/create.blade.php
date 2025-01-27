<!-- resources/views/tasks/create.blade.php -->
@extends('layouts.app')

@section('title', 'Создать задачу')

@section('content')
    <h1>Создать задачу</h1>

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название задачи</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="project_id" class="form-label">Проект</label>
            <select name="project_id" id="project_id" class="form-select">
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="assignee_id" class="form-label">Исполнитель</label>
            <select name="assignee_id" id="assignee_id" class="form-select">
                <option value="">Не назначено</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <select name="status" id="status" class="form-select">
                <option value="to_do">To Do</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Дедлайн</label>
            <input type="date" name="deadline" id="deadline" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Создать</button>
    </form>
@endsection
