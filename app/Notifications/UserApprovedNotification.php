<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserApprovedNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Ваш обліковий запис підтверджено')
            ->greeting('Вітаємо, ' . $notifiable->name . '!')
            ->line('Ваш обліковий запис успішно підтверджено адміністратором.')
            ->line('Тепер ви можете повноцінно користуватися всіма функціями системи.')
            ->action('Увійти до системи', url('/login'))
            ->line('Дякуємо за використання нашої платформи!');
    }
}

