<!-- resources/views/notifications.blade.php -->
@extends('layouts.app')

@section('title', 'Уведомления')

@section('content')
    <h1>Уведомления</h1>

    <ul class="list-group">
        @forelse ($notifications as $notification)
            <li class="list-group-item">
                {{ $notification->data['message'] }}
                <small class="text-muted d-block">Получено: {{ $notification->created_at->diffForHumans() }}</small>
            </li>
        @empty
            <li class="list-group-item">Уведомлений пока нет.</li>
        @endforelse
    </ul>
@endsection
