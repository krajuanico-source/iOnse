<?php

namespace App\Mail;

use App\Models\Applicants;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantStatusUpdated extends Mailable
{
  use Queueable, SerializesModels;

  public $applicant;
  public $status;
  public $remarks;

  public function __construct(Applicants $applicant, $status, $remarks = null)
  {
    $this->applicant = $applicant;
    $this->status = $status;
    $this->remarks = $remarks;
  }

  public function build()
  {
    return $this->subject('Your Application Status Has Been Updated')
      ->view('emails.applicant_status_updated');
  }
}
