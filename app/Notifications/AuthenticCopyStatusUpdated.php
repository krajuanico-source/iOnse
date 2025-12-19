<?php

namespace App\Notifications;

use App\Models\AuthenticCopyRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AuthenticCopyStatusUpdated extends Notification
{
  use Queueable;

  public $request;

  public function __construct(AuthenticCopyRequest $request)
  {
    $this->request = $request;
  }

  public function via($notifiable)
  {
    return ['mail', 'database']; // Send both email & database notification
  }

  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject('Your CPR Request Status Updated')
      ->greeting("Hello {$notifiable->name},")
      ->line("The status of your CPR authentic copy request (#{$this->request->id}) has been updated.")
      ->line("New status: {$this->request->status}")
      ->action('View Request', url('/')) // optionally link to request page
      ->line('Thank you for using our system.');
  }

  public function toDatabase($notifiable)
  {
    return [
      'title' => 'CPR Request Status Updated',
      'message' => "Your request (#{$this->request->id}) status is now {$this->request->status}.",
      'request_id' => $this->request->id,
    ];
  }
}
