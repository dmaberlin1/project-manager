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
