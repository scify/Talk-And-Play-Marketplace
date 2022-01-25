<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Resource\Resource;

class RejectionNotice extends Notification implements ShouldQueue
{
    use Queueable;
    protected Resource $coverResourceCardName;
    protected String $rejectionMessage;
    protected String $rejectionReason;
    protected String $username;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($coverResourceCardName, $rejectionMessage, $rejectionReason, $username)
    {
        $this->afterCommit = true;
        $this->coverResourceCardName = $coverResourceCardName;
        $this->rejectionReason= $rejectionReason;
        $this->rejectionMessage = $rejectionMessage;
        $this->username = $username;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable){

        $url = Route('resources.my_profile');
        return (new MailMessage)
            ->greeting('Greetings '.$this->username.'! Thank you for using our platform to support people fighting with brain paralysis.')
            ->subject('Talk and Play Marketplace: Your submitted package was rejected')
            ->line('We regret to inform you that your submitted package titled "'.$this->coverResourceCardName.'" was rejected by a moderator')
            ->line('Reason for rejection: "'.$this->rejectionReason.'"')
            ->line('Moderator comments: "'.$this->rejectionMessage.'"')
            ->line("Exercise Name:\t".$this->coverResourceCardName)
            ->line("User Name:\t".$notifiable->name)
            ->line("User Email:\t".$notifiable->email)
            ->action('View Submitted Packages', $url);
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
