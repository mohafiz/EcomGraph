<?php

namespace App\Notifications;

use App\Models\Promo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use NotificationChannels\Telegram\TelegramMessage;

class NewPromo extends Notification
{
    use Queueable;

    public $promo;

    /**
     * Create a new notification instance.
     */
    public function __construct(Promo $promo)
    {
        $this->promo = $promo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toTelegram($notifiable)
    {
        $discount = $this->promo->discountType == "percentage" ? "50%" : "$50";
        Lang::setLocale($notifiable->default_language);

        return TelegramMessage::create()
            ->to($notifiable->chat_id)
            ->content(Lang::get('messages.new_promo', ['discount' => $discount, 'code' => $this->promo->code]));
    }
}
