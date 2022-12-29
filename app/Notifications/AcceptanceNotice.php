<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptanceNotice extends Notification implements ShouldQueue {
    use Queueable;

    protected String $coverResourceCardName;
    protected String $username;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($coverResourceCardName, $username) {
        $this->afterCommit = true;
        $this->coverResourceCardName = $coverResourceCardName;
        $this->username = $username;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        $url = Route('resources_packages.my_packages') . '#approved';

        return (new MailMessage)
            ->greeting('Greetings ' . $this->username . '! Thank you for using our platform to support people fighting with brain paralysis.')
            ->subject('Talk and Play Marketplace: Your submitted package titled "' . $this->coverResourceCardName . '" was approved!')
            ->action('See your approved packages', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
            //
        ];
    }
}
