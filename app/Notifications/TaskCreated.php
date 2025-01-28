<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCreated extends Notification
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Новая задача: ' . $this->task->title)
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Для вас была создана новая задача.')
            ->line('Название задачи: ' . $this->task->title)
            ->line('Дедлайн: ' . $this->task->deadline->format('d.m.Y H:i'))
            ->action('Посмотреть задачу', url('/tasks/' . $this->task->id))
            ->line('Спасибо за использование нашего сервиса!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
