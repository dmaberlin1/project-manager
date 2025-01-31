<!-- resources/views/projects/show.blade.php -->
@extends('layouts.app')

@section('title', 'Просмотр проекта')

@section('content')
    <h1>{{ $project->name }}</h1>
    <p><strong>Описание:</strong> {{ $project->description }}</p>
    <p><strong>Статус:</strong> {{ ucfirst($project->status) }}</p>

    <h2>Список задач</h2>
    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-primary mb-3">Добавить задачу</a>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Название</th>
            <th>Исполнитель</th>
            <th>Статус</th>
            <th>Дедлайн</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($project->tasks as $task)
            <tr>
                <td>{{ $task->name }}</td>
                <td>{{ $task->assignee->name ?? 'Не назначено' }}</td>
                <td>{{ ucfirst($task->status) }}</td>
                <td>{{ $task->deadline ? $task->deadline->format('d.m.Y') : 'Без срока' }}</td>
                <td>
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-info">Просмотр</a>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-warning">Редактировать</a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить задачу?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Задач пока нет.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
