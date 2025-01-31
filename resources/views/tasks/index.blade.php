<!-- resources/views/tasks/index.blade.php -->
@extends('layouts.app')

@section('title', 'Список задач')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Список задач</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Создать задачу</a>
    </div>

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
        @forelse ($tasks as $task)
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

<script>
    window.Echo.channel('tasks')
        .listen('TaskCreated', (e) => {
            alert(`Новая задача создана: ${e.task.name}`);
            location.reload();
        });
</script>
