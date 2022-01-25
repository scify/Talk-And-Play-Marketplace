<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Resource\ResourcesPackage;
use App\Models\Resource\Resource;


class AdminNotice extends Notification implements ShouldQueue
{
    use Queueable;
    protected ResourcesPackage $package;
    protected String $coverResourceCardName;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($package, $coverResourceCardName)
    {
        $this->afterCommit = true;
        $this->package = $package;
        $this->coverResourceCardName = $coverResourceCardName;

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
    public function toMail($notifiable)
    {

        $url = Route('resources_packages.my_packages');
        return (new MailMessage)
            ->greeting('Submitted Package Details')
            ->subject('TnP: Confirm New Package Submission / '.$this->coverResourceCardName)
            ->line("Package ID:\t".$this->package->id)
            ->line("Package Name:\t".$this->coverResourceCardName)
            ->line("User Name:\t".$notifiable->name)
            ->line("User Email:\t".$notifiable->email)
            ->line("User ID:\t".$this->package->creator_user_id)
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
