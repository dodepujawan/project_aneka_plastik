<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class ProductSavedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $userName;

    /**
     * Create a new notification instance.
     *
     * @param string $userName
     * @return void
     */
    public function __construct($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'firebase'];
    }

    /**
     * Notifikasi untuk disimpan ke database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => "{$this->userName} added a new product.",
            'type' => 'product_saved',
            'user' => $this->userName
        ];
    }

    /**
     * Notifikasi yang dikirimkan ke Firebase.
     *
     * @param  mixed  $notifiable
     * @return \Kreait\Firebase\Messaging\CloudMessage
     */
    public function toFirebase($notifiable)
    {
        $message = CloudMessage::new()
            ->withTarget('token', $notifiable->firebase_token)  // Ganti dengan target sesuai yang diinginkan
            ->withNotification(FirebaseNotification::create(
                'New Product Added',  // Judul notifikasi
                "{$this->userName} added a new product."  // Isi pesan
            ))
            ->withData([
                'type' => 'product_saved',
                'invoice' => 'new_product_invoice_number',  // Ganti sesuai data yang dibutuhkan
            ]);

        return $message;
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
                    ->line('A new product has been added.')
                    ->action('View Product', url('/products'))
                    ->line('Thank you for using our application!');
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
            'message' => "{$this->userName} added a new product.",
            'type' => 'product_saved',
        ];
    }
}
