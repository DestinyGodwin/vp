<?php

namespace App\Notifications\v1;

use App\Models\WantedProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewWantedProductNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $wantedProduct;

    public function __construct(WantedProduct $wantedProduct)
    {
        $this->wantedProduct = $wantedProduct;
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
        ->subject('New Wanted Product Alert')
        ->greeting('Hello!')
        ->line('A buyer is looking for: ' . $this->wantedProduct->name)
        ->line('Category: ' . $this->wantedProduct->category->name)
        ->line('You might have this product in your store.')
        ->action('Check Now', url('/'))
        ->line('Thank you for using our app!');
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
