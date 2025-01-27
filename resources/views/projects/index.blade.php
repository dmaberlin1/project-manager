<!-- resources/views/projects/index.blade.php -->
@extends('layouts.app')

@section('title', 'Список проектов')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Список проектов</h1>
        @if (Auth::user()->role !== 'user')
            <a href="{{ route('projects.create') }}" class="btn btn-primary">Создать проект</a>
        @endif
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($projects as $project)
            <tr>
                <td>{{ $project->name }}</td>
                <td>{{ $project->description }}</td>
                <td>{{ ucfirst($project->status) }}</td>
                <td>
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-info">Просмотр</a>
                    @if (Auth::user()->role !== 'user')
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить проект?')">Удалить</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Проектов пока нет.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <h2>Погода в Киеве</h2>
    @if ($weather)
        <p>Температура: {{ $weather['main']['temp'] }} °C</p>
        <p>Описание: {{ $weather['weather'][0]['description'] }}</p>
    @else
        <p>Не удалось загрузить данные о погоде.</p>
    @endif

    <h2>Репозитории GitHub</h2>
    @if ($repos)
        <ul>
            @foreach ($repos as $repo)
                <li><a href="{{ $repo['html_url'] }}" target="_blank">{{ $repo['name'] }}</a></li>
            @endforeach
        </ul>
    @else
        <p>Не удалось загрузить список репозиториев.</p>
    @endif

@endsection
