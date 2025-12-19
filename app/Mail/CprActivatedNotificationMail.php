<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Cpr;

class CprActivatedNotificationMail extends Mailable
{
  use Queueable, SerializesModels;

  public $cpr;
  public $requestorName;
  public $requestorEmail;

  public function __construct(Cpr $cpr, $requestorName, $requestorEmail)
  {
    $this->cpr = $cpr;
    $this->requestorName = $requestorName;
    $this->requestorEmail = $requestorEmail;
  }

  public function build()
  {
    return $this->subject('CPR Activation Notification')
      ->view('emails.cpr_activated_notification');
  }
}
