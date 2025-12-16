<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Application Status Update</title>
</head>

<body>
  <p>Dear {{ $applicant->first_name }} {{ $applicant->last_name }},</p>

  <p>Your application status has been updated to: <strong>{{ $status }}</strong>.</p>

  @if($remarks)
  <p>Remarks: {{ $remarks }}</p>
  @endif

  <p>Thank you for applying!</p>

  <p>Regards,<br>HR Department</p>
</body>

</html>
