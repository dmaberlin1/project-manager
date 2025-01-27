<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <h1>Добро пожаловать, {{ auth()->user()->name }}!</h1>

    <p>Используйте меню для управления проектами, задачами и просмотра уведомлений.</p>
@endsection
