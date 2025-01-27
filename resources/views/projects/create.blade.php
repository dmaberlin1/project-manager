<!-- resources/views/projects/create.blade.php -->
@extends('layouts.app')

@section('title', 'Создать проект')

@section('content')
    <h1>Создать проект</h1>

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название проекта</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <select name="status" id="status" class="form-select" required>
                <option value="active">Активный</option>
                <option value="completed">Завершен</option>
                <option value="archived">Архивирован</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Создать</button>
    </form>
@endsection
