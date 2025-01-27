<!-- resources/views/projects/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Редактировать проект')

@section('content')
    <h1>Редактировать проект</h1>

    <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Название проекта</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $project->name }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea name="description" id="description" class="form-control">{{ $project->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <select name="status" id="status" class="form-select" required>
                <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>Активный</option>
                <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Завершен</option>
                <option value="archived" {{ $project->status === 'archived' ? 'selected' : '' }}>Архивирован</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
    </form>
@endsection
