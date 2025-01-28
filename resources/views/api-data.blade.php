@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Данные из внешних API</h1>

        <div>
            <h3>Погода</h3>
            <form id="weather-form">
                <input type="text" name="location" placeholder="Введите город" value="Kyiv">
                <button type="submit">Получить погоду</button>
            </form>
            <div id="weather-data"></div>
        </div>

        <div>
            <h3>Репозитории GitHub</h3>
            <form id="github-form">
                <input type="text" name="username" placeholder="Введите имя пользователя" value="octocat">
                <button type="submit">Получить репозитории</button>
            </form>
            <div id="github-data"></div>
        </div>
    </div>

    <script>
        document.getElementById('weather-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const location = e.target.location.value;

            fetch(`/api/weather?location=${location}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('weather-data').innerHTML = JSON.stringify(data);
                });
        });

        document.getElementById('github-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const username = e.target.username.value;

            fetch(`/api/github-repos?username=${username}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('github-data').innerHTML = JSON.stringify(data);
                });
        });
    </script>
@endsection
