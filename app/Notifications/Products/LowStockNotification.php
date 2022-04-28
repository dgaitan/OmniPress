<?php

namespace App\Notifications\Products;

use App\Models\WooCommerce\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Product $product;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'slack'];
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
                    ->from('no-reply@kindhumans.com', 'Kindhumans Admin')
                    ->subject('Product Low of Stock Warning in Kindhumans')
                    ->greeting('Hello Kindhumans Team.')
                    ->line(sprintf(
                        'This is a little warning to letting you know that product with #%s - %s is low of stock.',
                        $this->product->product_id,
                        $this->product->name
                    ))
                    ->action('Edit Product', $this->product->getStorePermalink())
                    ->line('Kindhumans Development Team. Regards!');
    }

    /**
     * Notify via slack
     *
     * @return void
     */
    public function toSlack()
    {
        return (new SlackMessage)
            ->content('Product Low Stock Warning.')
            ->attachment(function ($attachment) {
                $attachment->title($this->product->name, $this->product->getStorePermalink())
                    ->fields([
                        'Product ID' => $this->product->product_id,
                        'Stock Quantity' => $this->product->stock_quantity
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
