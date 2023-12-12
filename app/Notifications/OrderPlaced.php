<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderPlaced extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toTelegram($notifiable)
    {
        $data   = [];
        $order  = [];

        foreach ($this->order->products as $product) {
            
            $item = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->pivot->quantity,
                'total' => $product->price * $product->pivot->quantity
            ];

            array_push($order, $item);
        }

        $data['order'] = $order;
        $data['total'] = $this->order->totalPrice;

        return TelegramMessage::create()
            ->to($notifiable->chat_id)
            ->content("You have successfully placed an order!\nHere is your order details\n\n" . json_encode($data['order']) . "\n\n The total price is " . $data['total']);
    }
}
