<!-- resources/views/tasks/show.blade.php -->
@extends('layouts.app')

@section('title', 'Просмотр задачи')

@section('content')
    <h1>{{ $task->name }}</h1>

    <p><strong>Проект:</strong> {{ $task->project->name }}</p>
    <p><strong>Исполнитель:</strong> {{ $task->assignee->name ?? 'Не назначено' }}</p>
    <p><strong>Статус:</strong> {{ ucfirst($task->status) }}</p>
    <p><strong>Дедлайн:</strong> {{ $task->deadline ? $task->deadline->format('d.m.Y') : 'Без срока' }}</p>

    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">Редактировать</a>
    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline-block">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" onclick="return confirm('Удалить задачу?')">Удалить</button>
    </form>
@endsection
