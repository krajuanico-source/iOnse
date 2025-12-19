<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AuthenticCopyRequested extends Notification
{
  use Queueable;

  public function __construct(
    public $request, // AuthenticCopyRequest instance
    public $user     // Employee/User who made the request
  ) {}

  /**
   * The notification's delivery channels.
   */
  public function via($notifiable)
  {
    return ['database'];
  }

  /**
   * Store the notification in the database.
   */
  public function toDatabase($notifiable)
  {
    return [
      'title' => 'Authentic Copy Requested',
      'message' => $this->user->name . ' requested an authentic copy.',
      'request_id' => $this->request->id,
    ];
  }
}
