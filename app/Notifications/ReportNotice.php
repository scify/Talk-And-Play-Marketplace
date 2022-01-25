<?php

namespace App\Notifications;

use App\Models\Resource\ResourcesPackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Resource\Resource;
use App\Models\User;
class ReportNotice extends Notification implements ShouldQueue
{
    use Queueable;
    protected String $coverResourceCardName;
    protected ResourcesPackage $package;
    protected String $reportComment;
    protected String $reportReason;
    protected User $creator;
    protected User $reporter;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($package, $coverResourceCardName, $reportComment, $reportReason, $creator, $reporter)
    {
        $this->afterCommit = true;
        $this->package = $package;
        $this->coverResourceCardName = $coverResourceCardName;
        $this->rejectionReason= $reportReason;
        $this->rejectionMessage = $reportComment;
        $this->creator = $creator;
        $this->reporter = $reporter;
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

        $url = Route('administration.packages_management') . '#pending';
        return (new MailMessage)
            ->greeting('Greetings Admin! Please review the reported package')
            ->subject('Talk and Play Administration: Package REPORTED /  ' . $this->coverResourceCardName)
            ->line("Exercise ID:\t" . $this->package->id)
            ->line("Exercise Name:\t" . $this->coverResourceCardName)
            ->line("Creator Name:\t" . $this->creator->name)
            ->line("Creator Email:\t" . $this->creator->email)
            ->line("Creator ID:\t" . $this->package->creator_user_id)
            ->line("")
            ->line("Reporter Name:\t" . $this->reporter->name)
            ->line("Reporter Email:\t" . $this->reporter->email)
            ->line("Reporter ID:\t" . $this->reporter->id)
            ->action('Manage Submitted Packages', $url);
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
