<h3>CPR Activation Notification</h3>

<p>Dear {{ $requestorName }},</p>

<p>Your requested rating period has been activated:</p>

<ul>
  <li>CPR ID: {{ $cpr->id }}</li>
  <li>Rating Period: {{ $cpr->rating_period ?? $cpr->created_at->format('Y-m-d') }}</li>
  <li>Status: {{ $cpr->status }}</li>
</ul>

<p>You can now update the status of the rating period as needed.</p>

<p>Thank you.</p>