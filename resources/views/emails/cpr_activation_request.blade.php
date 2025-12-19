<h3>CPR Activation Request</h3>

<p>Dear HR,</p>

<p>A CPR activation request has been submitted for the following rating period:</p>

<ul>
  <li>CPR ID: {{ $cpr->id }}</li>
  <li>Rating Period: {{ $cpr->rating_period ?? $cpr->created_at->format('Y-m-d') }}</li>
  <li>Requested By: {{ $requestorName }} ({{ $requestorEmail }})</li>
</ul>

<p>Please review and take the necessary action.</p>

<p>Thank you.</p>