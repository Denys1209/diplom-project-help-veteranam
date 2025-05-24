<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserRejectedNotification extends Notification
{
    use Queueable;

    protected $reason;

    public function __construct($reason = null)
    {
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Ваш обліковий запис відхилено')
            ->greeting('Шановний(а) ' . $notifiable->name . ',')
            ->line('На жаль, ваш обліковий запис не було підтверджено адміністратором.');

        if ($this->reason) {
            $mail->line('Причина відхилення: ' . $this->reason);
        }

        return $mail
            ->line('Якщо у вас є питання, будь ласка, зверніться до служби підтримки.')
            ->line('Дякуємо за розуміння.');
    }
}
