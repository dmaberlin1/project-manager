---Замените your_app_id, your_app_key, your_app_secret, и your_cluster на ваши реальные данные из Pusher.

---broadcasting у меня нету
Конфигурация Pusher в config/broadcasting.php
Убедитесь, что в конфигурационном файле broadcasting.php настроен драйвер для Pusher:

php
Copy
Edit
'pusher' => [
'driver' => 'pusher',
'key' => env('PUSHER_APP_KEY'),
'secret' => env('PUSHER_APP_SECRET'),
'app_id' => env('PUSHER_APP_ID'),
'options' => [
'cluster' => env('PUSHER_APP_CLUSTER'),
'useTLS' => true,
],
],

---Настройка .env для API
Добавьте следующие строки в .env:

OPENWEATHERMAP_API_KEY=your_openweathermap_api_key
GITHUB_PERSONAL_ACCESS_TOKEN=your_github_personal_access_token


---6. Регистрация задачи в App\Console\Kernel
   В app/Console/Kernel.php добавьте команду в метод schedule:

protected function schedule(Schedule $schedule)
{
$schedule->command('api:update')->hourly();
}


---Пример вызова задачи в контроллере:

use App\Jobs\SendBulkEmails;

$emails = ['user1@example.com', 'user2@example.com'];
$message = 'Это тестовое уведомление.';

SendBulkEmails::dispatch($emails, $message);

--Пример вызова задачи с задержкой в контроллере:
use App\Jobs\DeleteProject;
use App\Models\Project;

$project = Project::find($id);
DeleteProject::dispatch($project)->delay(now()->addDays(7)); // Удаление через 7 дней


---Настройка SMTP
В файле .env укажите настройки для SMTP:

dotenv
Copy
Edit
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io # Или другой SMTP-сервер
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@taskmanager.com
MAIL_FROM_NAME="Task Manager"
Если у вас еще нет настроек SMTP, вы может

---Пример вызова уведомления:

php
Copy
Edit
use App\Notifications\TaskCreated;
use App\Models\Task;

// В контроллере:
$task = Task::find($taskId);
$task->assignee->notify(new TaskCreated($task));


---Пример вызова уведомления:

php
Copy
Edit
use App\Notifications\TaskCompleted;
use App\Models\Task;

// В контроллере:
$task = Task::find($taskId);
$task->project->manager->notify(new TaskCompleted($task));


---Пример вызова уведомления:

php
Copy
Edit
use App\Notifications\ProjectCompleted;
use App\Models\Project;

// В контроллере:
$project = Project::find($projectId);
foreach ($project->users as $user) {
$user->notify(new ProjectCompleted($project));
}


---Добавьте каналы в Blade-шаблон. Например, для отображения обновлений статуса задачи:
html
Copy
Edit
<script>
    Echo.private(`tasks.${taskId}`)
        .listen('TaskStatusUpdated', (e) => {
            console.log('Task Updated:', e);
            // Реактивное обновление данных в интерфейсе
            document.getElementById('task-status').innerText = e.status;
        });
</script>
