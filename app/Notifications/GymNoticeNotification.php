<?php

namespace App\Notifications;

use App\Models\Notice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GymNoticeNotification extends Notification
{
    use Queueable;

    private Notice $notice;

    public function __construct(Notice $notice)
    {
        $this->notice = $notice;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->notice->title)
                    ->greeting('Hola,')
                    ->line($this->notice->content)
                    ->action('Ir al tablero', url('/dashboard'))
                    ->line('Gracias por usar GymUdec.');
    }

    public function toArray($notifiable)
    {
        return [
            'notice_id' => $this->notice->id,
            'title' => $this->notice->title,
            'content' => $this->notice->content,
            'type' => $this->notice->type,
        ];
    }
}
