<?php

namespace App\Notifications\Printforia;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class OrderWasShipped extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toSlack()
    {
        return (new SlackMessage)
            ->content(sprintf('Printforia Order %s was shipped!', $this->order->printforia_order_id))
            ->attachment(function ($attachment) {
                $attachment->title('Order Summary', $this->order->getPermalinkOnStore())
                    ->fields([
                        'Kindhumans Order ID' => $this->order->order->order_id,
                        'Date Completed' => $this->order->date_created->format('F j, Y'),
                        'Payment Method' => $this->order->getPaymentMethodName(),
                        'Total' => $this->order->getTotal(),
                    ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
