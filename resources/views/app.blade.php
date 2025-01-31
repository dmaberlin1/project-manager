<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Управление проектами</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('projects.index') }}">Проекты</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('tasks.index') }}">Задачи</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('notifications') }}">Уведомления</a></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-link nav-link">Выход</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main class="container mt-4">
    @yield('content')
</main>
</body>
</html>
